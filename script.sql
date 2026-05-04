ALTER TABLE ventas
ADD COLUMN total_qr FLOAT NULL AFTER nro_ticket,
ADD COLUMN total_efectivo FLOAT NULL AFTER total_qr;

ALTER TABLE arqueo_caja
ADD COLUMN qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER efectivo_en_caja;

ALTER TABLE arqueo_caja
ADD COLUMN total_efectivo_qr_en_caja  decimal(11,2) NOT NULL DEFAULT '0.00' AFTER qr_en_caja;

-- $2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG  --> admin