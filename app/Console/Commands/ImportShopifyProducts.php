<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportShopifyProducts extends Command
{
    protected $signature = 'shopify:import
        {file=storage/app/products/cadenas.json : Ruta del JSON}
        {--category= : Slug de la categoría destino (opcional)}';

    protected $description = 'Importa productos Shopify a MongoDB con categorías automáticas o manuales';

    public function handle(): int
    {
        $filePath = base_path($this->argument('file'));
        $manualCategorySlug = trim((string) $this->option('category'));

        if (!File::exists($filePath)) {
            $this->error("No existe el archivo: {$filePath}");
            return self::FAILURE;
        }

        $json = json_decode(File::get($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('JSON inválido: ' . json_last_error_msg());
            return self::FAILURE;
        }

        if (empty($json)) {
            $this->warn('El archivo no contiene productos.');
            return self::SUCCESS;
        }

        $items = isset($json['shopify_id']) ? [$json] : $json;

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($items as $item) {
            try {
                $shopifyId = $item['shopify_id'] ?? null;

                if (!$shopifyId) {
                    $skipped++;
                    $this->warn('Producto omitido: no tiene shopify_id');
                    continue;
                }

                $name = trim((string) ($item['title'] ?? 'Producto'));
                $baseProductSlug = $this->resolveProductSlug($item, $name, $shopifyId);
                $summary = $this->cleanText($item['summary'] ?? null);
                $parsed = $this->parseSummary($summary);

                $variants = collect($item['variants'] ?? [])
                    ->map(function ($variant) {
                        return [
                            'variant_id' => $variant['id'] ?? null,
                            'title' => $variant['title'] ?? null,
                            'sku' => $variant['sku'] ?? null,
                            'price' => $this->normalizePrice($variant['price'] ?? 0),
                            'compare_price' => isset($variant['compare_at_price']) && $variant['compare_at_price'] !== null
                                ? $this->normalizePrice($variant['compare_at_price'])
                                : null,
                            'available' => (bool) ($variant['available'] ?? false),
                            'option1' => $variant['option1'] ?? null,
                            'option2' => $variant['option2'] ?? null,
                            'option3' => $variant['option3'] ?? null,
                            'options' => array_values(array_filter($variant['options'] ?? [], fn ($v) => $v !== null && $v !== '')),
                            'requires_shipping' => (bool) ($variant['requires_shipping'] ?? false),
                            'taxable' => (bool) ($variant['taxable'] ?? false),
                            'inventory_management' => $variant['inventory_management'] ?? null,
                            'barcode' => $variant['barcode'] ?? null,
                        ];
                    })
                    ->values()
                    ->toArray();

                $images = collect($item['media'] ?? [])
                    ->map(function ($media) {
                        return [
                            'media_id' => $media['id'] ?? null,
                            'src' => $this->fixUrl($media['src'] ?? null),
                            'alt' => $media['alt'] ?? null,
                            'width' => $media['width'] ?? null,
                            'height' => $media['height'] ?? null,
                            'position' => $media['position'] ?? null,
                            'aspect_ratio' => isset($media['aspect_ratio']) ? (float) $media['aspect_ratio'] : null,
                            'media_type' => $media['media_type'] ?? 'image',
                        ];
                    })
                    ->filter(fn ($img) => !empty($img['src']))
                    ->values()
                    ->toArray();

                $mainImage = $this->fixUrl($item['main_image'] ?? null);
                if (!$mainImage && !empty($images)) {
                    $mainImage = $images[0]['src'] ?? null;
                }

                $existing = Product::where('shopify_id', (int) $shopifyId)->first();

                /*
                |--------------------------------------------------------------------------
                | CATEGORÍA PRINCIPAL
                |--------------------------------------------------------------------------
                | Si el usuario manda --category, se usa esa.
                | Si no, se detecta automáticamente desde el title.
                |--------------------------------------------------------------------------
                */
                $categorySlug = $manualCategorySlug !== ''
                    ? Str::slug($manualCategorySlug)
                    : $this->detectCategorySlug($name, $item);

                if (!$categorySlug) {
                    $categorySlug = 'otros';
                }

                $category = Category::firstOrCreate(
                    ['slug' => $categorySlug],
                    [
                        'name' => $this->humanizeSlug($categorySlug),
                        'is_active' => true,
                        'sort_order' => 0,
                    ]
                );

                $newCategoryIds = [(string) $category->id];
                $newCategorySlugs = [$category->slug];
                $newCategoryNames = [$category->name];

                $existingCategoryIds = $existing
                    ? $this->normalizeArrayField($existing->category_ids ?? [])
                    : [];

                $existingCategorySlugs = $existing
                    ? $this->normalizeArrayField($existing->category_slugs ?? [])
                    : [];

                $existingCategoryNames = $existing
                    ? $this->normalizeArrayField($existing->category_names ?? [])
                    : [];

                $mergedCategoryIds = array_values(array_unique(array_filter(array_merge(
                    $existingCategoryIds,
                    $newCategoryIds
                ), fn ($value) => $value !== null && $value !== '')));

                $mergedCategorySlugs = array_values(array_unique(array_filter(array_merge(
                    $existingCategorySlugs,
                    $newCategorySlugs
                ), fn ($value) => $value !== null && $value !== '')));

                $mergedCategoryNames = array_values(array_unique(array_filter(array_merge(
                    $existingCategoryNames,
                    $newCategoryNames
                ), fn ($value) => $value !== null && $value !== '')));

                $productData = [
                    'shopify_id' => (int) $shopifyId,
                    'shopify_handle' => $item['raw_handle'] ?? $item['slug'] ?? $baseProductSlug,
                    'shopify_url' => $item['url'] ?? null,

                    'name' => $name,
                    'slug' => $baseProductSlug,

                    'description' => $summary,
                    'short_description' => Str::limit((string) $summary, 180),

                    'brand' => $parsed['brand'],
                    'material' => $parsed['material'],
                    'color' => $parsed['color'],

                    'weight' => $parsed['weight'],
                    'length' => $parsed['length'],
                    'thickness' => $parsed['thickness'],

                    'main_image' => $mainImage,

                    'category_ids' => $mergedCategoryIds,
                    'category_slugs' => $mergedCategorySlugs,
                    'category_names' => $mergedCategoryNames,

                    'promotion_ids' => $existing
                        ? $this->normalizeArrayField($existing->promotion_ids ?? [])
                        : [],

                    'variants' => $variants,
                    'images' => $images,

                    'status' => 'active',
                    'is_featured' => $existing ? (bool) ($existing->is_featured ?? false) : false,

                    'meta_title' => $name,
                    'meta_description' => Str::limit((string) $summary, 160),

                    'badge_labels' => is_array($item['badge_labels'] ?? null) ? $item['badge_labels'] : [],
                    'status_badges' => is_array($item['status_badges'] ?? null) ? $item['status_badges'] : [],

                    'raw_product' => $item,
                ];

                if ($existing) {
                    $existing->update($productData);
                    $updated++;
                    $this->line("↻ Actualizado: {$name} [{$category->slug}]");
                } else {
                    Product::create($productData);
                    $created++;
                    $this->line("✔ Creado: {$name} [{$category->slug}]");
                }
            } catch (\Throwable $e) {
                $skipped++;
                $this->error("Error importando producto: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Creados: {$created}");
        $this->info("Actualizados: {$updated}");
        $this->warn("Omitidos/Error: {$skipped}");

        return self::SUCCESS;
    }

    private function detectCategorySlug(string $name, array $item = []): ?string
    {
        $source = mb_strtolower(trim($name));

        $map = [
            'anillo' => 'anillos',
            'topos' => 'topos',
            'cadena' => 'cadenas',
            'dije' => 'dijes',
            'pulsera' => 'pulseras',
            'arete' => 'aretes',
            'argolla' => 'argollas',
            'candonga' => 'candongas',
            'ear cuff' => 'ear-cuffs',
            'piercing' => 'piercings',
            'tobillera' => 'tobilleras',
            'broquel' => 'broqueles',
            'collar' => 'collares',
            'set' => 'sets',
        ];

        foreach ($map as $keyword => $slug) {
            if (Str::startsWith($source, $keyword . ' ') || $source === $keyword) {
                return $slug;
            }
        }

        $rawHandle = mb_strtolower((string) ($item['raw_handle'] ?? $item['slug'] ?? ''));

        foreach ($map as $keyword => $slug) {
            if (Str::startsWith($rawHandle, Str::slug($keyword) . '-') || $rawHandle === Str::slug($keyword)) {
                return $slug;
            }
        }

        return null;
    }

    private function resolveProductSlug(array $item, string $name, int|string $shopifyId): string
    {
        $candidate =
            $item['slug']
            ?? $item['raw_handle']
            ?? data_get($item, 'raw_product.handle')
            ?? Str::slug($name);

        $candidate = Str::slug((string) $candidate);

        if ($candidate === '') {
            $candidate = 'producto';
        }

        return "{$candidate}-{$shopifyId}";
    }

    private function cleanText(?string $text): ?string
    {
        if (!$text) {
            return null;
        }

        $text = strip_tags($text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim((string) $text);
    }

    private function fixUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        return str_starts_with($url, '//') ? 'https:' . $url : $url;
    }

    private function normalizePrice($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            $number = (float) $value;
            return $number >= 10000000 ? $number / 100 : $number;
        }

        $clean = preg_replace('/[^\d.,-]/', '', (string) $value);
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }

    private function humanizeSlug(string $slug): string
    {
        return Str::title(str_replace('-', ' ', $slug));
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

        if (preg_match('/Color:\s*([^•]+)/iu', $summary, $m)) {
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

        if (preg_match('/Calidad:\s*([^•]+)/iu', $summary, $m)) {
            $result['material'] = trim($m[1]);
        }

        return $result;
    }

    private function normalizeArrayField($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter($decoded, fn ($item) => $item !== null && $item !== ''));
            }

            return $value !== '' ? [$value] : [];
        }

        return [];
    }
}