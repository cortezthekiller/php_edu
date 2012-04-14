<?
include("/var/seguridad/db.inc.php");
include("func.inc.php");

$table   = "alumnos";
$profile = "profile.php";

open_html_tags("Update profile");

$query  = "UPDATE ".$table." SET nombre='".$_POST['nombre']."', ";

/* Campo correspondiente a la foto */
/* Comprobar que realmente se ha enviado un archivo y que no está vacío */
if(isset($_FILES['image']) && $_FILES['image']['size']) {

   /* Nombre del archivo temporal con la imagen en el server */
   $tmp_name = $_FILES['image']['tmp_name'];

   /* Leer el archivo temporal de la imagen */
   $fp   = fopen($tmp_name, 'rb');
   //$data = fread($fp, $_FILES['image']['size']));
   $data = fread($fp, filesize($tmp_name));
   $data = addslashes($data);
   fclose($fp);

   $query .= "foto='".$data."', ";
}

/* Pasar la fecha de nacimiento a formato DATE MySQL */
$query .= "nacimiento='";
$query .= $_POST['year']."-".$_POST['month']."-".$_POST['day']."', ";

$query .= "apellido1='".$_POST['apellido1']."', ";
$query .= "apellido2='".$_POST['apellido2']."', ";
$query .= "DNI='".$_POST['DNI']."', ";
$query .= "email='".$_POST['email']."', ";
$query .= "curso='".$_POST['curso']."' ";
$query .= "WHERE id=".$_POST['id'];

//debug_msg($query);

mysql_query($query, $link)
   or die("Error UPDATE: ".mysql_error());

echo "Perfil del alumno actualizado con éxito<br/>";

echo "<a href='".$profile."?id=".$_POST['id']."'>Volver</a>";
close_html_tags();
mysql_close($link);
?>
