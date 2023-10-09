DELIMITER $$
	CREATE PROCEDURE sp_procesar_compra(idU INT(11),nombres VARCHAR(250),orden VARCHAR(500),envio VARCHAR(255),fechaP VARCHAR(255),idPa VARCHAR(250),cant VARCHAR(250),totalP VARCHAR(250),estat VARCHAR(250),numOrden VARCHAR(11),nom VARCHAR(255),ape VARCHAR(255),email VARCHAR(255),tlf VARCHAR(255),ciud VARCHAR(255), col VARCHAR(255),edo VARCHAR(255),dir VARCHAR(255),codigo VARCHAR(255))
    BEGIN
        DECLARE iD INT;
        DECLARE exist INT;
        DECLARE procces  BOOLEAN;

        INSERT INTO folios (id_usuario,nombres,orden,envio,paqueteria,cantidad,total,estatus,fecha) VALUES (idU,nombres,orden,envio,idPa,cant,totalP,estat,fechaP);
        SET iD = LAST_INSERT_ID();
        IF iD > 0 THEN
            INSERT INTO detalle_orden (nombres_usuarios,apellidos_usuarios,correo_usuario,telefono_usuario,ciudad,colonia,estado,direccion,codigo_postal,folio) VALUES(nom,ape,email,tlf,ciud,col,edo,dir,codigo,id);
            UPDATE usuario SET orden=numOrden WHERE id=idU;
            DELETE FROM carrito WHERE id_usuario = idU;

            SET iD = idU;
        ELSE
            SET iD = 0;
        END IF;
        SELECT iD;
    END; $$
DELIMITER ;
