*Para usar la pagina web necesitan tener el xamp instalado y pegar los archivos php en la ubcacion q lo tienen instalado. ej: 
C:\xampp\htdocs

------Login
*Luego para usar el login, tienen que usar el usuario de la db. En este caso seguramente usen root y la password. Con eso hace la conexion y 
sirve para probar otros usuarios cuando los creen

------Mediciones
Para el mediciones.php van a necesitar estos procedimientos tener en su db. GetUsuarios`() y GetConceptos`(). Esto esta haciendo un insert asi 
 $insertQuery = "INSERT INTO Mediciones (periodo, fechaLectura, lectura, Cuenta_idCuenta) VALUES ('$periodo', '$fechaLectura', $lectura, $cuenta_id)";
 De momento esta asi pero hay q llevarlo a un procedimiento. Que cuando este hecho lo modifico en el php.

-----Facturar
 En facturar necesita un procedimiento que se tiene q llamar CALL GenerateFactura(periodo); 

