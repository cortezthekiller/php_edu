<?
session_start();   /* El formulario llamante se pasa como variable de sesión */

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current = basename($_SERVER['SCRIPT_NAME']);

open_html_tags("Script altas alumnos");
echo_username();

debug_msg($current.": recibe sesión userid: ".$_SESSION['userid']);

$table  = "alumnos";                                     /* Tabla principal */
$tables = array("matematicas","historia","tecnologia");  /* Child tables    */
$avatar = "default.bin";
$default_image = TRUE;

/* Establecer conexión con el servidor */
/* Conectamos con el servidor y comprobamos la conexión */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die('Error en la conexión: '.mysql_error());

mysql_select_db($mysql_db, $link);

$fields = "(";   /* String con los fields de la query INSERT */
$values = "(";   /* String con los valores a insertar        */

if(!empty($_POST['dni'])) {
   $fields .= "DNI";
   $values .= "'".$_POST['dni']."'";
}

if(!empty($_POST['nombre'])) {
   $fields .= ",nombre";
   $values .= ",'".$_POST['nombre']."'";
}

if(!empty($_POST['apellido1'])) {
   $fields .= ",apellido1";
   $values .= ",'".$_POST['apellido1']."'";
}

if(!empty($_POST['apellido2'])) {
   $fields .= ",apellido2";
   $values .= ",'".$_POST['apellido2']."'";
}

if(!empty($_POST['day']) && !empty($_POST['month']) && !empty($_POST['year'])) {
   $fields .= ",nacimiento";
   $fecha = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
   $values .= ",'".$fecha."'";
}

if(!empty($_POST['email'])) {
   $fields .= ",email";
   $values .= ",'".$_POST['email']."'";
}

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

   $fields .= ",foto";
   $values .= ",'".$data."'";

   $default_image = FALSE; /* no hace falta insertar avatar por defecto */
}

if(!empty($_POST['curso'])) {
   $fields .= ",curso";
   $values .= ",'".$_POST['curso']."'";
}

$fields .= ") ";
$values .= ")";

/* Query INSERT nuevo registro en la tabla */
$query = "INSERT INTO ".$table." ".$fields." VALUES ".$values;

mysql_query($query, $link) or die("Error INSERT: ".mysql_error());

if($default_image) {

   /* Ruta completa del archivo con el blob para el avatar por defecto */
   $avatar = dirname(__FILE__)."/".$avatar;

   $query  = "UPDATE ".$table." SET foto=LOAD_FILE('".$avatar."') ";
   $query .= "WHERE DNI='".$_POST['dni']."'";

   mysql_query($query, $link)
      or die("Error UPDATE blob ".mysql_error());
}

/* Para mantener la integridad referencial, habría que añadir el    */
/* registro correspondiente en las tablas vinculadas (asignaturas). */
foreach($tables as $tablename) {
   $insert = "INSERT INTO ".$tablename." (DNI) VALUES ('".$_POST['dni']."')";
   mysql_query($insert, $link)
      or die("Error en INSERT: ".mysql_error($link));
}

mysql_close($link);

echo "Registro nuevo alumno completado satisfactoriamente.<br>";

/* Interfaz principal :      */
/*  - Cambiar contraseña     */
/*  - Cerrar sesión          */
/*  - Altas, etc.            */
include("interface.inc.php");


/* Mostrar enlace para volver al formulario.       */
html_link_back("Volver al formulario de altas");

close_html_tags();
?>
