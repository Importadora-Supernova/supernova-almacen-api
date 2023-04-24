<!-- DELIMITER $$ 
DROP PROCEDURE IF EXISTS `SP_CONSULTAS` $$
CREATE PROCEDURE `SP_CONSULTAS`() -- NO TENEMOS DATOS DE ENTRADA
BEGIN
    DECLARE _PRODUCTOS_TOTALES		INT;
    DECLARE _FOLIOS_TOTALES		    INT;
    
    -- Nos permite obtener el total de los productos
	SET _PRODUCTOS_TOTALES = (SELECT SUM(almacen) as totales FROM productos);
    
  
    -- Nos permite obtener todos los folios
    SET _FOLIOS_TOTALES = (SELECT (SUM(id)  as folios_totales FROM folios);
    
    -- bien ahora solo debemos retornar los 3 datos mediante otro SELECT
    SELECT _PRODUCTOS_TOTALES AS PT, _FOLIOS_TOTALES AS FT;
    
END$$
DELIMITER ; -- EL DELIMITADOR VUELVE A SER ";" -->

DROP PROCEDURE IF EXISTS `SP_CORTE` $$
CREATE PROCEDURE `SP_CORTE`() -- NO TENEMOS DATOS DE ENTRADA
BEGIN
    DECLARE _MONTO_TOTAL      INT;
    DECLARE _MONTO_OTRO       INT;
    DECLARE _TOTAL_EFECTIVO   INT;
    DECLARE _TOTAL_PAQUETERIA INT;
    DECLARE _TOTAL_VENTA      INT;
    DECLARE _TOTAL_COSTO      INT;
    
    -- Nos permite obtener el total de montos pagos
	SET _MONTO_TOTAL = (SELECT SUM(monto) as total FROM pagos WHERE fecha LIKE CONCAT('%',fecha_corte,'%'));

    -- Nos permite obtener el total de monto otro
    SET _MONTO_OTRO  = 
    

    -- bien ahora solo debemos retornar los 3 datos mediante otro SELECT
    SELECT _MONTO_TOTAL AS MT;
    
END

BEGIN
    DECLARE _MONTO_TOTAL           INT;
    DECLARE _MONTO_OTRO            INT;
    DECLARE _TOTAL_EFECTIVO        INT;
    DECLARE _TOTAL_PAQUETERIA      INT;
    DECLARE _TOTAL_IVA             INT;
    DECLARE _TOTAL_SEGURO          INT;
    DECLARE _TOTAL_SALDO_PENDIENTE INT;
    DECLARE _TOTAL_VENTA           INT;
    DECLARE _TOTAL_COSTO           INT;
    
    -- Nos permite obtener el total de montos pagos
	SET _MONTO_TOTAL = (SELECT SUM(monto) as total FROM pagos WHERE fecha LIKE CONCAT(fecha_corte,'%'));

    -- Nos permite obtener el total de monto otro
    SET _MONTO_OTRO  = (SELECT SUM(monto) as total_otro FROM pagos WHERE fecha LIKE CONCAT('%',fecha_corte,'%') AND banco!="lemussa-bbva" AND banco!="website-bbva" AND banco!="isn-hsbc" AND banco!="isn-bbva" AND banco!="efectivo");
    
    -- Nos permite obtener el total en efectivo 
    SET _TOTAL_EFECTIVO = (SELECT SUM(monto) as total_efectivo FROM pagos WHERE banco="efectivo" AND fecha LIKE CONCAT('%',fecha_corte,'%'));
    
	-- Nos permite obtener el totalde la paqueteria
    SET _TOTAL_PAQUETERIA = (SELECT SUM(venta_paqueteria) as total_paqueteria FROM folios WHERE  fecha_pago LIKE CONCAT('%',fecha_corte,'%'));
    
    -- Nos permite traer el total de iva
    SET _TOTAL_IVA = (SELECT SUM(iva) as total_iva FROM folios WHERE  fecha_pago LIKE CONCAT('%',fecha_corte,'%'));
    
    -- Nos permite traer el total de seguro
    SET _TOTAL_SEGURO = (SELECT SUM(seguro) as seguro FROM folios WHERE  fecha_pago LIKE CONCAT('%',fecha_corte,'%'));
    
    -- Nos permite traer el total del saldo pendiente
    SET _TOTAL_SALDO_PENDIENTE = (SELECT SUM(saldo_pendiente) as total_saldo_pendiente FROM folios WHERE  fecha_pago LIKE CONCAT('%',fecha_corte,'%'));
    
    -- Nos permite traer el total de la venta
    SET _TOTAL_VENTA = (SELECT SUM(cantidad*precio) as total_venta FROM registro_usuario WHERE fecha_procesado LIKE CONCAT('%',fecha_corte,'%') AND estatus="Pagado");
    
    -- Nos permite traer el total del costo
    SET _TOTAL_COSTO = (SELECT SUM(p.precio_costo*r.cantidad) as total_costo FROM productos p INNER JOIN registro_usuario r ON p.id = r.id_producto WHERE r.estatus="Pagado" AND r.fecha_procesado LIKE CONCAT('%',fecha_corte,'%'));
    
    
    -- bien ahora solo debemos retornar los 3 datos mediante otro SELECT
    SELECT _MONTO_TOTAL AS total, _MONTO_OTRO AS total_otro, _TOTAL_EFECTIVO AS total_efectivo, _TOTAL_PAQUETERIA AS total_paqueteria, _TOTAL_IVA AS total_iva, _TOTAL_SEGURO AS seguro, _TOTAL_SALDO_PENDIENTE AS total_saldo_pendiente, _TOTAL_VENTA AS total_venta, _TOTAL_COSTO AS total_costo;
    
END