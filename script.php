<html>
<head><title>Script insertar imagen</title></head>
<body>
<?
include("/var/seguridad/mysql.inc.php");

$table = "images";

/* Comprobar que realmente se ha enviado un archivo y que no está vacío */
if(isset($_FILES['image']) && $_FILES['image']['size']) {

   echo "Size = ".$_FILES['image']['size']."<br/>";
   /* Nombre del archivo temporal con la imagen en el server */ 
   $tmp_name = $_FILES['image']['tmp_name'];

   /* Leer el archivo temporal de la imagen */
   $fp   = fopen($tmp_name, 'rb');
   //$data = fread($fp, $_FILES['image']['size']));
   $data = fread($fp, filesize($tmp_name));
   $data = addslashes($data); 
   fclose($fp);

   /* Conectarse al servidor de base de datos */
   $link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
           or die("Error en la conexión: ".mysql_error());

   mysql_select_db ($mysql_db, $link);

   /* Query INSERT nuevo registro en la tabla */
   $query  = "INSERT INTO ".$table;
   $query .= " (image) VALUES ('".$data."')";
   mysql_query($query, $link) or die("Error INSERT: ".mysql_error());

   echo "Imagen guardada en la base de datos<br/>";
   mysql_close($link);

} else {
   /* Aviso antes de volver al formulario */
   echo "Por favor, seleccione un archivo<br/>";
} 

/* Enlace para volver al formulario */
echo "<a href='index.php'>Volver al formulario</a>";
?>

</body>
</html>
