-- =============================================================================
-- Reparación opcional: ventas con promo visible en UI pero guardadas sin descuento
-- (casos creados antes del fix de ajax/ventas.ajax.php).
--
-- NO ejecutar a ciegas. Revisar primero:
--   SELECT id, codigo, total, total_bruto, total_descuento, estado_pago
--   FROM ventas
--   WHERE COALESCE(total_descuento,0)=0
--   ORDER BY id DESC LIMIT 20;
--
-- La recálculo correcta debe hacerse desde la aplicación (editar cuenta pendiente
-- y volver a guardar) para que ControladorVentas::aplicarPromocionesAListaProductos
-- regenere detalle y totales.
-- =============================================================================

SELECT
  v.id,
  v.codigo,
  v.total,
  v.total_bruto,
  v.total_descuento,
  v.estado_pago,
  COALESCE(SUM(dv.descuento_total), 0) AS descuento_en_detalle
FROM ventas v
LEFT JOIN detalle_venta dv ON dv.id_venta = v.id
WHERE v.estado = 1
GROUP BY v.id, v.codigo, v.total, v.total_bruto, v.total_descuento, v.estado_pago
HAVING COALESCE(v.total_descuento, 0) = 0
   AND COALESCE(SUM(dv.descuento_total), 0) = 0
ORDER BY v.id DESC
LIMIT 50;
