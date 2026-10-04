-- Agregar nuevo tipo de gasto para sueldos y salarios
INSERT INTO tipo_gasto (nombre) VALUES ('Sueldos y salarios');

-- Los totales de venta estaban en FLOAT y la suma podía variar un centavo
-- entre el cuadre, el gráfico, el Excel y el PDF.
-- Se redondea cada venta a centavos y se guarda como DECIMAL.
-- fecha = fecha evita que ON UPDATE CURRENT_TIMESTAMP
-- reescriba la fecha si algún monto cambia de centavo.

UPDATE ventas
SET total = ROUND(IFNULL(total, 0), 2),
    total_efectivo = ROUND(IFNULL(total_efectivo, 0), 2),
    total_qr = ROUND(IFNULL(total_qr, 0), 2),
    total_pagado = ROUND(IFNULL(total_pagado, 0), 2),
    cambio = ROUND(IFNULL(cambio, 0), 2),
    fecha = fecha;

ALTER TABLE ventas
  MODIFY total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  MODIFY total_efectivo DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  MODIFY total_qr DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  MODIFY total_pagado DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  MODIFY cambio DECIMAL(12,2) NOT NULL DEFAULT 0.00;