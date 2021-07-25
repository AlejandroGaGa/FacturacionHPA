DELIMITER $
CREATE TRIGGER entradas_A_I AFTER INSERT on producto FOR EACH ROW
BEGIN
INSERT INTO entradas(codproducto,cantidad,precio,usuario_id) VALUES(new.codproducto,new.existencia,new.precio,new.usuario_id);
END; $
DELIMITER ;