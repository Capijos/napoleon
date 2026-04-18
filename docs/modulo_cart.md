# Módulo Carrito de Compras - Napoleone Joyas

## Resumen

Se implementó un sistema de carrito de compras funcional con cierre de pedido por WhatsApp, preparado para habilitar pagos online en el futuro.

---

## 1. Estructura de Archivos Creados/Modificados

### Controladores

- `app/Http/Controllers/CartController.php` - **NUEVO**
    - Gestión completa del carrito
    - Endpoints AJAX para agregar, eliminar, actualizar
    - Integración WhatsApp

### Rutas

- `routes/web.php` - **MODIFICADO**
    - Añadidas rutas `/cart` y `/checkout`

### Vistas

- `resources/views/cart/index.blade.php` - **NUEVO**
    - Carrito con estructura idéntica a napoleonejoyas.co/cart
- `resources/views/checkout/index.blade.php` - **NUEVO**
    - Página de checkout visual
    - Métodos de pago deshabilitados (preparados para futuro)
    - Botón WhatsApp

- `resources/views/product/show.blade.php` - **MODIFICADO**
    - Botón "Agregar al Carrito" conectado al endpoint

- `resources/views/components/header.blade.php` - **MODIFICADO**
    - Enlace al carrito
    - Contador dinámico de items

---

## 2. Endpoints Creados

| Método | Ruta                 | Función                |
| ------ | -------------------- | ---------------------- |
| GET    | `/cart`              | Mostrar carrito        |
| POST   | `/cart/add`          | Agregar producto       |
| POST   | `/cart/remove`       | Eliminar producto      |
| POST   | `/cart/update`       | Actualizar cantidad    |
| POST   | `/cart/clear`        | Vaciar carrito         |
| GET    | `/cart/count`        | Contador AJAX          |
| GET    | `/checkout`          | Página checkout        |
| POST   | `/checkout/whatsapp` | Enviar pedido WhatsApp |

---

## 3. Flujo Implementado

```
Producto → Click "Agregar al Carrito" → AJAX POST /cart/add
                                              ↓
                                     Backend busca por shopify_id
                                              ↓
                                     Guarda en MongoDB (Cart)
                                              ↓
                              Respuesta JSON con items_count
                                              ↓
                         Actualiza contador header + Redirección
                                              ↓
                              /cart → Carrito con productos
                                              ↓
                     /checkout → Checkout visual
                                              ↓
                 Botón WhatsApp → Mensaje dinámico → wa.me
```

---

## 4. Datos del Producto en Carrito

El sistema almacena:

- `product_id` - ID de producto (shopify_id)
- `variant_id` - ID de variante (opcional)
- `name` - Nombre del producto
- `image` - Imagen principal
- `quantity` - Cantidad
- `unit_price` - Precio unitario
- `subtotal` - Total por item
- `variant_name` - Nombre variante
- `sku` - SKU

---

## 5. Integración con Vista de Producto

### Botón "Agregar al Carrito"

- Located en `resources/views/product/show.blade.php`
- Usa JavaScript AJAX con `fetch()`
- Envía: `product_id`, `variant_id`, `quantity`, `unit_price`
- Muestra feedback visual:
    - Loading: spinner + "Agregando..."
    - Éxito: mensaje verde + redirección a `/cart`
    - Error: mensaje rojo

### Datos envados desde la vista

```javascript
{
    product_id: '7834173997171',  // shopify_id
    variant_id: null,
    quantity: 1,
    unit_price: 150000
}
```

---

## 6. Modelo Cart

Located en `app/Models/Cart.php`

### Métodos del modelo

- `addItem($item)` - Agregar item al carrito
- `removeItem($productId, $variantId)` - Eliminar item
- `clear()` - Vaciar carrito
- `recalculate()` - Recalcular subtotal

### Campos

- `items` - Array de items
- `subtotal` - Subtotal
- `shipping_cost` - Costo envío (futuro)
- `tax` - Impuesto (futuro)
- `discount` - Descuento (futuro)
- `total` - Total
- `status` - Estado del carrito

---

## 7. WhatsApp Integration

El botón principal en `/checkout` genera un mensaje dinámico:

```
* Nuevo Pedido - Napoleone Joyas *

1. Producto Nombre
   Cantidad: 1 x $150.000
   Subtotal: $150.000

2. Producto Nombre 2
   Cantidad: 2 x $200.000
   Subtotal: $400.000

---------------------------
Subtotal: $550.000
Envío: $15.000
*TOTAL: $565.000*

Por favor confirmar disponibilidad.
```

Redirect a: `https://wa.me/573103243890?text=...`

---

## 8. Preparado para Pagos Online

### Métodos de pago deshabilitados en checkout:

- Tarjeta de Crédito/Débito
- Mercado Pago

### Para habilitar:

1. Deshabilitar clase `payment-method--disabled`
2. Crear integración con pasarela de pago
3. Modificar método `sendToWhatsApp()` o crear `processPayment()`

---

## 9. URL del Proyecto

**Servidor activo:** `http://127.0.0.1:8000`

### URLs funcionales:

- `http://127.0.0.1:8000/` - Inicio
- `http://127.0.0.1:8000/cart` - Carrito
- `http://127.0.0.1:8000/checkout` - Checkout

---

## 10. Observaciones Pendientes

1. **Laragon configuration**: El proyecto debe correrse en puerto 8000 (`php artisan serve --port=8000`)

2. **Cards de producto**: Los listados de categoría/home usan HTML embebido de Shopify. Agregar "Agregar al Carrito" requiere modificar el JavaScript global o crear componentes separados.

3. **Busqueda de productos**: El CartController busca por `shopify_id` primero, luego intenta `_id` de MongoDB.

4. **Validación**: El precio mínimo es 0, pero se recomienda validar que sea mayor a 0 en producción.

---

## 11. Pruebas Esperadas

1. ✓ Acceder a `/cart` - Mostrar carrito vacío
2. ✓ Ir a producto y dar "Agregar al Carrito" - Producto en carrito
3. ✓ Actualizar cantidad en carrito - Precios se recalculan
4. ✓ Eliminar producto - Remover del carrito
5. ✓ Ir a `/checkout` - Ver resumen del pedido
6. ✓ Click "Enviar por WhatsApp" - Redirección a WhatsApp

---

## 12. Comandos Útiles

```bash
# Iniciar servidor
php artisan serve --port=8000

# Ver rutas
php artisan route:list --name=cart

# Limpiar cache de rutas
php artisan route:clear

# Verificar sintaxis PHP
php -l app/Http/Controllers/CartController.php
```

---

_Documentación generada: Abril 2026_
