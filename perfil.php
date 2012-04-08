<?
include_once("/var/seguridad/db.inc.php");

$table   = "alumnos";
$script  = "alumno.php";
$current = $_SERVER['SCRIPT_NAME'];

/* Conexión con el servidor de base de datos */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die("Error en la conexión: ".mysql_error());

mysql_select_db ($mysql_db, $link);

echo "<html>";
echo "<head>"; 
echo "<title>Mostrar Perfil Alumno</title>";
echo "</head>"; 
echo "<body>";

/* El formulario llama al mismo script que lo contiene */
/* así que comprobamos si venimos del formulario.      */      
if(isset($_GET['enviar']) && $_GET['dni']) {

   /* Mostrar los datos del alumno en formato tabla html */
   echo "<table align='center' border='1' bgcolor='#F0FFFF'>";
   echo "<tr>";
   echo "<th>Foto</th>";
   echo "<th>Alumno</th>";
   echo "</tr>";

   echo "<tr>";

   $query  = "SELECT * FROM ".$table." WHERE DNI='".$_GET['dni']."'";
   $result = mysql_query($query, $link);
   $row    = mysql_fetch_array($result, MYSQL_ASSOC); 

   /* Llamar por método GET al script que visualiza la imagen */ 
   echo "<td><img src='".$script."?id=".$_GET['dni']."'/></td>";

   /* Mostrar nombre y apellidos en la tabla html a la derecha de la foto */
   $alumno = $row['apellido1']." ".$row['apellido2'].", ".$row['nombre'];
   echo "<td>".$alumno."</td>";
   echo "</tr><br/><br/>";

   /* Mostrar enlace para volver al formulario (script actual) */
   echo "<tr><td colspan='2' align='center'>";
   echo "<a href='".$current."'>Seleccionar otro alumno</a>";
   echo "</td></tr>";
   echo "</table>";

   /* Liberar recursos cuando ya no son necesarios */
   mysql_free_result($result);
   mysql_close($link);

} else {

   /* La primera vez que se llame a este archivo empezaremos aquí */

   echo "<form name='perfiles' method='GET' action=''>";
   echo "<table align='center' frame='box' bgcolor='#F0FFFF'>";

   /* Consultamos la tabla 'alumnos' para presentar en el formulario un   */
   /* desplegable con el nombre y apellidos de cada alumno guardada en la */
   /* tabla. El select del desplegable va dentro de celdas de una tabla html. */

   $query = "SELECT * FROM ".$table;
   $result = mysql_query($query, $link);

   if($result) {
      echo "<tr><td colspan='2'>";
      echo "<select name='dni'>";
      echo "<option value=''>Seleccionar alumno</option>";

      /* Mostramos el nombre y apellidos, pero como value pasamos el DNI */
      while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $alumno  = $fila['apellido1']." ".$fila['apellido2'].", ";
         $alumno .= $fila['nombre'];
         $option  = "<option value='".$fila['DNI']."'>".$alumno."</option>";
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
   echo "<td>Mostrar perfil</td>";
   echo "<td><input type='submit' name='enviar' value='OK'/></td>";
   echo "</tr>";
   echo "</table>";
   echo "</form>";
}

echo "</body>";
echo "</html>";
?>
