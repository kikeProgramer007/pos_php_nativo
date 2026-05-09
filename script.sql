ALTER TABLE ventas
ADD COLUMN total_qr FLOAT NULL AFTER nro_ticket,
ADD COLUMN total_efectivo FLOAT NULL AFTER total_qr;

ALTER TABLE arqueo_caja
ADD COLUMN qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER efectivo_en_caja;

ALTER TABLE arqueo_caja
ADD COLUMN total_efectivo_qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER qr_en_caja;



ALTER TABLE arqueo_caja
ADD COLUMN  monto_ventas_efectivo  decimal(11,2)  NULL DEFAULT '0.00' AFTER Bs020;

ALTER TABLE arqueo_caja
ADD COLUMN monto_ventas_qr  decimal(11,2)  NULL DEFAULT '0.00' AFTER monto_ventas_efectivo;


-- $2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG  --> admin

-- INSERTAR VALORES A total_qr y total_efectivo en tabla ventas:

-- Antes de ejecutar el UPDATE, puedes verificar qué filas serán afectadas:
SELECT 
    id,
    codigo,
    tipo_pago,
    total,
    total_efectivo,
    total_qr
FROM ventas
WHERE tipo_pago IN ('Efectivo', 'QR');

-- Actulizar columnas nuevas, este script evita sobrescribir registros que ya tienen valor:
UPDATE ventas
SET 
    total_efectivo = CASE 
        WHEN tipo_pago = 'Efectivo' 
             AND (total_efectivo IS NULL OR total_efectivo = 0)
        THEN total
        ELSE total_efectivo
    END,
    
    total_qr = CASE 
        WHEN tipo_pago = 'QR'
             AND (total_qr IS NULL OR total_qr = 0)
        THEN total
        ELSE total_qr
    END;
