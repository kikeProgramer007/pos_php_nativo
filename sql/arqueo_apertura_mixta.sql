-- Apertura mixta: monto_apertura (total) = efectivo + QR
-- Seguro para producción: columnas nuevas con DEFAULT 0 y copia del saldo actual a efectivo.

ALTER TABLE arqueo_caja
  ADD COLUMN monto_apertura_efectivo DECIMAL(11,2) NOT NULL DEFAULT 0.00 AFTER monto_apertura,
  ADD COLUMN monto_apertura_qr DECIMAL(11,2) NOT NULL DEFAULT 0.00 AFTER monto_apertura_efectivo;

UPDATE arqueo_caja
SET monto_apertura_efectivo = monto_apertura,
    monto_apertura_qr = 0.00;
