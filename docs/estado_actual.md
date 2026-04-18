# Estado Actual del Carrito de Compras - Napoleone Joyas

**Fecha:** 17 Abril 2026  
**Proyecto:** Carrito de Compras Ecommerce  
**Stack:** Laravel 11 + MongoDB + Blade

---

## Resumen Ejecutivo

El sistema de carrito de compras ha sido completamente reimplementado y normalizado. El flujo actual funciona correctamente:

- ✅ Agregar productos desde cards/listado
- ✅ Popup de confirmación con imagen y precio
- ✅ Contador del header se actualiza
- ✅ Mini-cart drawer muestra productos
- ✅ Página /cart muestra productos
- ✅ Cambiar cantidad (update)
- ✅ Eliminar productos (remove)

---

## Arquitectura del Sistema

### Rutas del Carrito

| Método | Ruta               | Controlador             | Descripción          |
| ------ | ------------------ | ----------------------- | -------------------- |
| POST   | `/api/cart/add`    | CartController@add      | Agregar producto     |
| GET    | `/api/cart/count`  | CartController@getCount | Obtener contador     |
| GET    | `/api/cart/mini`   | CartController@mini     | Renderizar mini-cart |
| POST   | `/api/cart/update` | CartController@update   | Actualizar cantidad  |
| POST   | `/api/cart/remove` | CartController@remove   | Eliminar producto    |
| GET    | `/cart`            | CartController@index    | Página del carrito   |
| GET    | `/checkout`        | CartController@checkout | Página de checkout   |

### Persistencia

- **Base de datos:** MongoDB (colección `carts`)
- **Modelo:** `App\Models\Cart`
- **Gestión de sesión:** localStorage + header `X-Cart-ID`

---

## Estructura de Datos

### Item del Carrito (Canónica)

```php
[
    'product_id' => string,        // ID único del producto (ej: "8222709383283")
    'variant_id' => string|null,    // ID de variante (ej: "43940004626547")
    'name' => string,           // Nombre del producto
    'image' => string,          // URL de imagen
    'quantity' => int,         // Cantidad
    'unit_price' => float,      // Precio unitario (en centavos)
    'subtotal' => float,       // Subtotal (quantity * unit_price)
    'variant_name' => string|null, // Nombre de variante
    'sku' => string|null,      // SKU del producto
]
```

### Respuestas API

**POST /api/cart/add**

```json
{
    "success": true,
    "message": "Producto agregado al carrito",
    "cart_id": "69e26798ce9826135e0c4142",
    "items_count": 3,
    "subtotal": 524000
}
```

**GET /api/cart/count**

```json
{ "count": 3 }
```

---

## Componentes del Frontend

### 1. app.blade.php

- Popup de confirmación "agregado al carrito"
- Funciones JS globales: `addToCart()`, `showCartAddedPopup()`, `updateCartCount()`
- Handler global para clicks en botones de cards

### 2. mini-cart.blade.php

- Drawer lateral derecho
- Botones +/- para cantidad
- Botón eliminar
- Subtotal

### 3. header.blade.php

- Ícono del carrito en header desktop
- Ícono del carrito en header mobile
- Contador dinámico del carrito

### 4. cart/index.blade.php

- Página completa del carrito
- Tabla de productos
- Controles de cantidad
- Eliminar producto
- Subtotal y total

---

## Flujo de Datos

### Agregar Producto (add)

```
1. Usuario hace click en "Agregar al Carrito" (card o detalle)
2. Frontend extrae: product_id, price, name, image del DOM
3. Fetch POST a /api/cart/add con X-Cart-ID header
4. Backend: getOrCreateCart() → addItem() → save()
5. Backend retorna: cart_id, items_count, subtotal
6. Frontend: guarda cart_id en localStorage
7. Frontend: muestra popup de confirmación
8. Frontend: actualiza contador
```

### Abrir Mini-Cart

```
1. Usuario hace click en ícono del carrito
2. openMiniCart() → añade clase is-open
3. reloadMiniCart() → fetch GET /api/cart/mini
4. Backend: consulta MongoDB por X-Cart-ID
5. Backend: renderiza mini-cart.blade.php con items
6. Frontend: reemplaza contenido del drawer
```

### Actualizar Cantidad (update)

```
1. Usuario hace click en +/-
2. Event listener captura click
3. updateMiniCartItem(productId, variantId, newQty)
4. Fetch POST a /api/cart/update
5. Backend: actualiza cantidad en el item
6. Backend: recalcula subtotal y total
7. Frontend: reloadMiniCart() + updateCartCount()
```

### Eliminar Producto (remove)

```
1. Usuario hace click en eliminar
2. Event listener captura click
3. removeMiniCartItem(productId, variantId)
4. Fetch POST a /api/cart/remove
5. Backend: removeItem() → save()
6. Backend: retorna items_count actualizado
7. Frontend: reloadMiniCart() + updateCartCount()
```

---

## Archivos Modificados

### Backend

| Archivo                                   | Cambios                                            |
| ----------------------------------------- | -------------------------------------------------- |
| `app/Http/Controllers/CartController.php` | Métodos add, update, remove, mini, getCount, index |
| `app/Models/Cart.php`                     | Métodos addItem, removeItem, recalculate           |

### Frontend

| Archivo                                          | Cambios                                        |
| ------------------------------------------------ | ---------------------------------------------- |
| `resources/views/app.blade.php`                  | Popup, funciones addToCart, cart_id management |
| `resources/views/components/mini-cart.blade.php` | Fetch calls, estructura de item                |
| `resources/views/components/header.blade.php`    | openMiniCart() link                            |
| `resources/views/product/show.blade.php`         | Botón add-to-cart                              |
| `resources/views/cart/index.blade.php`           | Fetch calls con X-Cart-ID                      |

### Rutas

| Archivo          | Cambios               |
| ---------------- | --------------------- |
| `routes/api.php` | Rutas API del carrito |
| `routes/web.php` | Rutas web (limpio)    |

---

## Problemas Resueltos

### 1. Error 419 (Page Expired)

**Causa:** CSRF token no se enviaba con fetch AJAX  
**Solución:** Crear rutas API dedicadas en `/api/*` sin protección CSRF

### 2. Carrito vacío en mini-cart

**Causa:** No se pasaba X-Cart-ID header en las peticiones  
**Solución:** Guardar cart_id en localStorage, enviar en todos los fetch

### 3. Update/Remove no funcionaban

**Causa:** Identificador inconsistente entre plantilla y payload  
**Solución:** Estandarizar a `product_id` en toda la cadena

### 4. Página /cart no mostraba productos

**Causa:** Sesión PHP no coordinada con API  
**Solución:** Aceptar `?cart_id=XXX` en query string

### 5. Índice único冲突 en MongoDB

**Causa:** Índice compuesto user_id + status fallaba al crear nuevos carts  
**Solución:** Generar IDs únicos con `uniqid()` + `time()`

---

## Pendientes /Mejoras Futuras

1. **Carrito guest vs user:** Actualmente solo guest - no hay login/registro
2. **Cupones/descuentos:** No implementado
3. **Stock validation:** No valida disponibilidad antes de agregar
4. **Sesión PHP:** Podría eliminarse completamente y usar solo localStorage
5. **Tests:** No hay tests automatizados

---

## Checklist de Validación

| Función                 | Estado | Notas                         |
| ----------------------- | ------ | ----------------------------- |
| Agregar desde card      | ✅     | Popup muestra imagen + precio |
| Agregar desde detalle   | ✅     | Popup muestra imagen + precio |
| Contador se actualiza   | ✅     | Header icon                   |
| Mini-cart abre          | ✅     | Drawer lateral                |
| Mini-cart muestra items | ✅     | Con productos agregados       |
| /cart muestra items     | ✅     | Con ?cart_id=XXX              |
| Cambiar cantidad        | ✅     | Botones +/- funciona          |
| Eliminar producto       | ✅     | Botón eliminar funciona       |
| Persistencia            | ✅     | tras recarga de página        |

---

## Endpoints para Testing

```bash
# Agregar producto
curl -X POST http://localhost:8000/api/cart/add \
  -H "Content-Type: application/json" \
  -H "X-Cart-ID: {cart_id}" \
  -d '{"product_id":"8222709383283","quantity":1,"unit_price":178000,"product_name":"Herraje"}'

# Obtener count
curl http://localhost:8000/api/cart/count -H "X-Cart-ID: {cart_id}"

# Mini-cart
curl http://localhost:8000/api/cart/mini -H "X-Cart-ID: {cart_id}"

# Update
curl -X POST http://localhost:8000/api/cart/update \
  -H "X-Cart-ID: {cart_id}" \
  -d '{"product_id":"8222709383283","quantity":2}'

# Remove
curl -X POST http://localhost:8000/api/cart/remove \
  -H "X-Cart-ID: {cart_id}" \
  -d '{"product_id":"8222709383283"}'

# Página /cart
curl "http://localhost:8000/cart?cart_id={cart_id}"
```

---

**Documento generado automáticamente el 17 de Abril de 2026**
