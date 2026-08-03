# Ofertas y Promociones — Guía rápida

## 1. Instalar base de datos

Ejecutar el script:

`sql/promociones_ofertas.sql`

en MySQL/MariaDB (phpMyAdmin, HeidiSQL o consola).

## 2. Cómo funciona

1. Menú **Ofertas y Promociones** (Admin / Supervisor).
2. Registrar promoción (nombre, fechas, prioridad, estado).
3. En el detalle: agregar **intervalos** y **productos vinculados**.
4. Al vender, el sistema suma la cantidad por producto, busca promociones vigentes y aplica el intervalo correspondiente.
5. El ticket muestra precio normal, descuento y total neto cuando hay promoción.

## 3. Selección de intervalo

Para la cantidad acumulada del producto:

- Se elige el intervalo donde `cantidad_minima <= cantidad` y (`cantidad_maxima` es NULL o `cantidad <= cantidad_maxima`).

## 4. Varias promociones

1. Mayor `prioridad`.
2. Si empatan, mayor descuento unitario.
3. Si siguen empatando, la más reciente (`fecha` / id).

No se acumulan dos promociones sobre el mismo producto.

## 5. Persistencia en `detalle_venta`

Se guardan: `precio_original`, `tipo_descuento`, `valor_descuento`, `descuento_unitario`, `descuento_total`, `id_promocion`, `id_intervalo_promocion`, `nombre_promocion`.  
`precio_venta` = precio unitario final cobrado.

## 6. Ventas pendientes

Al editar una cuenta pendiente se recalculan promociones vigentes. Las líneas existentes cargan su precio original histórico; productos nuevos solo reciben promociones aún vigentes.
