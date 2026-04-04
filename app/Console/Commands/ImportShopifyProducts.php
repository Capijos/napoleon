<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportShopifyProducts extends Command
{
    protected $signature = 'shopify:import 
                            {file=storage/app/products/cadenas.json : Ruta del archivo JSON}
                            {--category=cadenas : Slug de la categoría a agregar}';

    protected $description = 'Importa productos Shopify desde un JSON a products, product_variants y product_images';

    public function handle(): int
    {
        $filePath = base_path($this->argument('file'));
        $categorySlug = Str::slug($this->option('category') ?: 'cadenas');

        if (!File::exists($filePath)) {
            $this->error("No existe el archivo: {$filePath}");
            return self::FAILURE;
        }

        $json = File::get($filePath);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('JSON inválido: ' . json_last_error_msg());
            return self::FAILURE;
        }

        if (isset($data['shopify_id'])) {
            $data = [$data];
        }

        if (!is_array($data)) {
            $this->error('El archivo JSON debe contener un array de productos o un objeto de producto.');
            return self::FAILURE;
        }

        $category = Category::firstOrCreate(
            ['slug' => $categorySlug],
            [
                'name' => Str::title(str_replace('-', ' ', $categorySlug)),
                'description' => 'Categoría importada automáticamente',
                'is_active' => true,
            ]
        );

        $this->info("Categoría usada: {$category->name} ({$category->slug})");
        $this->newLine();

        $created = 0;
        $updated = 0;
        $failed = 0;
        $failedProducts = [];

        foreach ($data as $index => $item) {
            DB::beginTransaction();

            try {
                $shopifyId = $item['shopify_id'] ?? null;

                if (!$shopifyId) {
                    throw new \Exception('No tiene shopify_id');
                }

                $name = $this->safeString($item['title'] ?? 'Producto sin nombre', 255);
                $baseSlug = $item['slug'] ?? Str::slug(($name ?: 'producto') . '-' . $shopifyId);
                $slug = $this->makeUniqueProductSlug($baseSlug, $shopifyId);

                $summary = $this->normalizeSummary($item['summary'] ?? null);
                $mainImage = $this->normalizeUrl($item['main_image'] ?? null);
                $rawHandle = $this->safeString($item['raw_handle'] ?? null, 255);
                $shopifyUrl = $this->safeString($item['url'] ?? null, 500);

                $parsed = $this->parseSummary($summary);

                $existing = Product::where('shopify_id', $shopifyId)->first();

                $productData = [
                    'shopify_handle' => $rawHandle ?: $this->safeString($slug, 255),
                    'shopify_url' => $shopifyUrl,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $summary,
                    'short_description' => $this->safeString(Str::limit(strip_tags((string) $summary), 180, ''), 255),
                    'brand' => $this->safeString($parsed['brand'], 100),
                    'material' => $this->safeString($parsed['material'], 100),
                    'color' => $this->safeString($parsed['color'], 100),
                    'weight' => $parsed['weight'],
                    'length' => $parsed['length'],
                    'thickness' => $parsed['thickness'],
                    'main_image' => $mainImage,
                    'status' => 'active',
                    'is_featured' => false,
                    'meta_title' => $this->safeString($name, 255),
                    'meta_description' => $this->safeString(Str::limit(strip_tags((string) $summary), 160, ''), 255),
                    'badge_labels' => $this->normalizeArray($item['badge_labels'] ?? []),
                    'status_badges' => $this->normalizeArray($item['status_badges'] ?? []),
                    'raw_product' => $item['raw_product'] ?? $item,
                    'updated_at' => now(),
                ];

                if ($existing) {
                    // no toca la categoría principal si ya existe
                    $existing->update($productData);
                    $product = $existing;
                    $updated++;
                    $action = 'actualizado';
                } else {
                    // solo al crear por primera vez se define la categoría principal
                    $product = Product::create(array_merge($productData, [
                        'category_id' => $category->id,
                        'shopify_id' => $shopifyId,
                        'created_at' => now(),
                    ]));
                    $created++;
                    $action = 'creado';
                }

                // agrega la categoría nueva sin borrar las anteriores
                $product->categories()->syncWithoutDetaching([$category->id]);

                // Variantes
                $product->variants()->delete();

                foreach (($item['variants'] ?? []) as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'shopify_variant_id' => $variant['id'] ?? null,
                        'title' => $this->safeString($variant['title'] ?? null, 255),
                        'sku' => $this->sanitizeSku($variant['sku'] ?? null),
                        'price' => $this->normalizePrice($variant['price'] ?? 0),
                        'compare_price' => $this->normalizeNullablePrice($variant['compare_at_price'] ?? null),
                        'stock' => 0,
                        'available' => (bool) ($variant['available'] ?? false),
                        'option1' => $this->safeString($variant['option1'] ?? null, 255),
                        'option2' => $this->safeString($variant['option2'] ?? null, 255),
                        'option3' => $this->safeString($variant['option3'] ?? null, 255),
                        'requires_shipping' => (bool) ($variant['requires_shipping'] ?? true),
                        'taxable' => (bool) ($variant['taxable'] ?? false),
                        'inventory_management' => $this->safeString($variant['inventory_management'] ?? null, 100),
                        'barcode' => $this->safeString($variant['barcode'] ?? null, 255),
                        'options' => $this->normalizeArray($variant['options'] ?? []),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Imágenes
                $product->images()->delete();

                foreach (($item['media'] ?? []) as $media) {
                    $src = $this->normalizeUrl($media['src'] ?? null);

                    if (!$src) {
                        continue;
                    }

                    ProductImage::create([
                        'product_id' => $product->id,
                        'shopify_media_id' => $media['id'] ?? null,
                        'src' => $src,
                        'alt' => $this->safeString($media['alt'] ?? null, 255),
                        'width' => $this->toNullableInt($media['width'] ?? null),
                        'height' => $this->toNullableInt($media['height'] ?? null),
                        'position' => $this->toNullableInt($media['position'] ?? null),
                        'aspect_ratio' => $this->toNullableFloat($media['aspect_ratio'] ?? null),
                        'media_type' => $this->safeString($media['media_type'] ?? 'image', 50),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();

                $this->line("✔ Producto {$action}: {$product->name}");
            } catch (\Throwable $e) {
                DB::rollBack();
                $failed++;

                $failedItem = [
                    'index' => $index,
                    'shopify_id' => $item['shopify_id'] ?? null,
                    'title' => $item['title'] ?? null,
                    'slug' => $item['slug'] ?? null,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'payload' => $item,
                ];

                $failedProducts[] = $failedItem;

                $this->error(
                    "✘ Error en índice {$index} | shopify_id: " .
                    ($item['shopify_id'] ?? 'null') .
                    ' | ' .
                    $e->getMessage()
                );
            }
        }

        $errorFilePath = null;
        $retryFilePath = null;

        if (!empty($failedProducts)) {
            $errorDir = storage_path('app/import-errors');

            if (!File::exists($errorDir)) {
                File::makeDirectory($errorDir, 0755, true);
            }

            $timestamp = now()->format('Y_m_d_His');

            $errorFilePath = $errorDir . "/shopify_import_failed_{$timestamp}.json";
            $retryFilePath = $errorDir . "/shopify_import_retry_{$timestamp}.json";

            File::put(
                $errorFilePath,
                json_encode([
                    'created_at' => now()->toDateTimeString(),
                    'source_file' => $filePath,
                    'category' => $categorySlug,
                    'failed_count' => count($failedProducts),
                    'failed_products' => $failedProducts,
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            File::put(
                $retryFilePath,
                json_encode(
                    array_map(fn ($item) => $item['payload'], $failedProducts),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                )
            );
        }

        $this->newLine();
        $this->info('Importación terminada.');
        $this->info("Creados: {$created}");
        $this->info("Actualizados: {$updated}");
        $this->info("Fallidos: {$failed}");

        if ($errorFilePath) {
            $this->warn('Archivo de errores guardado en:');
            $this->line($errorFilePath);
        }

        if ($retryFilePath) {
            $this->warn('Archivo de reintento guardado en:');
            $this->line($retryFilePath);
        }

        return self::SUCCESS;
    }

    private function parseSummary(?string $summary): array
    {
        $result = [
            'color' => null,
            'weight' => null,
            'length' => null,
            'thickness' => null,
            'material' => null,
            'brand' => 'Napoleone',
        ];

        if (!$summary) {
            return $result;
        }

        $summary = $this->normalizeSummary($summary);

        if (preg_match('/Color:\s*(.+?)(?=Peso:|Largo:|Grosor:|Tipo de Broche:|Disponible para|Cuidados y Garantía|$)/iu', $summary, $m)) {
            $result['color'] = trim($m[1]);
        }

        if (preg_match('/Peso:\s*([\d.,]+)/iu', $summary, $m)) {
            $result['weight'] = (float) str_replace(',', '.', $m[1]);
        }

        if (preg_match('/Largo:\s*([\d.,]+)/iu', $summary, $m)) {
            $result['length'] = (float) str_replace(',', '.', $m[1]);
        }

        if (preg_match('/Grosor:\s*([\d.,]+)/iu', $summary, $m)) {
            $result['thickness'] = (float) str_replace(',', '.', $m[1]);
        }

        if (preg_match('/(Oro\s+Italiano\s+18K|Oro\s+18K|Plata|Acero)/iu', $summary, $m)) {
            $result['material'] = trim($m[1]);
        }

        return $result;
    }

    private function normalizeSummary(?string $summary): ?string
    {
        if (!$summary) {
            return null;
        }

        $summary = strip_tags($summary);
        $summary = preg_replace('/\s+/', ' ', $summary);
        $summary = str_replace([' ,', ' .'], [',', '.'], $summary);

        return trim($summary);
    }

    private function normalizeUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $url = trim($url);

        if (Str::startsWith($url, '//')) {
            return 'https:' . $url;
        }

        return $url;
    }

    private function sanitizeSku(?string $sku): ?string
    {
        if (!$sku) {
            return null;
        }

        $sku = trim($sku);
        $sku = preg_replace('/[^\pL\pN\-_\.\/]/u', '', $sku);

        return $this->safeString($sku, 255);
    }

    private function normalizePrice($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return round((float) $value, 2);
    }

    private function normalizeNullablePrice($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return round((float) $value, 2);
    }

    private function toNullableInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function toNullableFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function normalizeArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value === null) {
            return [];
        }

        return [$value];
    }

    private function safeString(?string $value, int $limit = 255): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return Str::limit($value, $limit, '');
    }

    private function makeUniqueProductSlug(string $baseSlug, int|string|null $shopifyId = null): string
    {
        $baseSlug = Str::slug($baseSlug);

        if (!$baseSlug) {
            $baseSlug = 'producto-' . ($shopifyId ?: Str::random(6));
        }

        $existing = Product::where('slug', $baseSlug)->first();

        if (!$existing) {
            return $baseSlug;
        }

        if ($shopifyId && (string) $existing->shopify_id === (string) $shopifyId) {
            return $baseSlug;
        }

        $slug = $baseSlug . '-' . $shopifyId;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($shopifyId, fn ($q) => $q->where('shopify_id', '!=', $shopifyId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $shopifyId . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
