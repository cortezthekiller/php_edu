<?
include_once("/var/seguridad/mysql.inc.php");

$table   = "images";
$script  = "mostrar.php";
$current = $_SERVER['SCRIPT_NAME'];

/* Conexión con el servidor de base de datos */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die("Error en la conexión: ".mysql_error());

mysql_select_db ($mysql_db, $link);

echo "<html>";
echo "<head>"; 
echo "<title>Mostrar Imágenes</title>";
echo "</head>"; 
echo "<body>";

/* El formulario llama al mismo script que lo contiene */
/* así que comprobamos si venimos del formulario.      */      
if(isset($_GET['enviar']) && $_GET['id']) {

   /* Llamar por método GET al script que visualiza la imagen */ 
   echo "<img src='".$script."?id=".$_GET['id']."'/><br/><br/>";

   /* Mostrar enlace para volver al script actual */
   echo "<a href='".$current."'>Seleccionar otra imagen</a>";

} else {

   /* La primera vez que se llame a este archivo empezaremos aquí */

   echo "<form name='mostrar_imgs' method='GET' action=''>";
   echo "<table align='center' frame='box' bgcolor='#F0FFFF'>";

   /* Consultamos la tabla 'images'  para presentar en el formulario    */
   /* un desplegable con los 'id' de cada imagen guardada en la tabla.  */ 
   /* El select del desplegables va dentro de celdas de una tabla html. */

   $query = "SELECT * FROM ".$table;
   $result = mysql_query($query, $link);

   if($result) {
      echo "<tr><td colspan='2'>";
      echo "<select name='id'>";
      echo "<option value=''>Seleccionar imagen</option>";

      while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $option = "<option value='".$fila['id']."'>".$fila['id']."</option>";
         echo $option;
      }

      echo "</select>";
      echo "</td></tr>";

      /* Liberar recursos cuando ya no son necesarios */
      mysql_free_result($result);
      mysql_close($link);
   }

   /* fila vacía en la tabla, por cuestiones estéticas */
   echo "<tr><td colspan='2' style='height: 20;'></td></tr>";

   /* Botón de envío en una celda de la tabla */
   echo "<tr>";
   echo "<td>Mostrar imagen</td>";
   echo "<td><input type='submit' name='enviar' value='OK'/></td>";
   echo "</tr>";
   echo "</table>";
   echo "</form>";
}

echo "</body>";
echo "</html>";
?>
