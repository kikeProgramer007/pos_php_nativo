ALTER TABLE ventas MODIFY fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE ventas
ADD COLUMN total_qr FLOAT NULL AFTER nro_ticket,
ADD COLUMN total_efectivo FLOAT NULL AFTER total_qr;

ALTER TABLE arqueo_caja
ADD COLUMN qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER efectivo_en_caja;

ALTER TABLE arqueo_caja
ADD COLUMN total_efectivo_qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER qr_en_caja;


-- NUEVO SCRIPT PARA REGISTRAR LOS MONTOS DE VENTAS EN EFECTIVO Y QR EN LA TABLA arqueo_caja, QUE REGISTRE LAS VENTAS QUE ESTEN ASOCIADO AL id_arqueo_caja de la venta

ALTER TABLE arqueo_caja
ADD COLUMN  monto_ventas_efectivo  decimal(11,2)  NULL DEFAULT '0.00' AFTER Bs020;

ALTER TABLE arqueo_caja
ADD COLUMN monto_ventas_qr  decimal(11,2)  NULL DEFAULT '0.00' AFTER monto_ventas_efectivo;

-- Verificar registros que necesitan actualización, este script es solo para consulta y no modifica datos:

SELECT 
    id,
    codigo,
    tipo_pago,
    total,
    total_efectivo,
    total_qr
FROM ventas
WHERE tipo_pago IN ('Efectivo', 'QR', 'Transferencia', 'Qr y Efectivo(Mixto)')
  AND (total_efectivo IS NULL OR total_qr IS NULL);

-- Actulizar columnas nuevas, este script evita sobrescribir registros que ya tienen valor:
UPDATE ventas
SET 
    total_efectivo = CASE 
        WHEN tipo_pago = 'Efectivo'
             AND (total_efectivo IS NULL OR total_efectivo = 0)
        THEN total

        WHEN tipo_pago = 'Qr y Efectivo(Mixto)'
             AND total_efectivo IS NULL
        THEN total

        ELSE total_efectivo
    END,

    total_qr = CASE 
        WHEN tipo_pago IN ('QR', 'Transferencia')
             AND (total_qr IS NULL OR total_qr = 0)
        THEN total

        WHEN tipo_pago = 'Qr y Efectivo(Mixto)'
             AND total_qr IS NULL
        THEN 0

        ELSE total_qr
    END
WHERE tipo_pago IN ('Efectivo', 'QR', 'Transferencia', 'Qr y Efectivo(Mixto)');


-- Actualizar arqueo_caja con los montos de ventas en efectivo y qr, este script evita sobrescribir registros que ya tienen valor:

UPDATE arqueo_caja ac
SET 
    ac.monto_ventas_efectivo = (
        SELECT IFNULL(SUM(
            CASE
                WHEN v.tipo_pago = 'Efectivo' THEN v.total
                WHEN v.tipo_pago = 'Qr y Efectivo(Mixto)' THEN v.total_efectivo
                ELSE 0
            END
        ), 0)
        FROM ventas v
        WHERE v.estado = 1
          AND v.id_arqueo_caja = ac.id
    ),

    ac.monto_ventas_qr = (
        SELECT IFNULL(SUM(
            CASE
                WHEN v.tipo_pago = 'QR' THEN v.total
                WHEN v.tipo_pago = 'Transferencia' THEN v.total
                WHEN v.tipo_pago = 'Qr y Efectivo(Mixto)' THEN v.total_qr
                ELSE 0
            END
        ), 0)
        FROM ventas v
        WHERE v.estado = 1
          AND v.id_arqueo_caja = ac.id
    );
