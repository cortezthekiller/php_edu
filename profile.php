<?
session_start();

include_once("/var/seguridad/db.inc.php");
include("func.inc.php");

$table   = "alumnos";
$script  = "alumno.php";
$current = basename($_SERVER['SCRIPT_NAME']);

open_html_tags("Mostrar Perfil Alumno");
echo_username();

debug_msg($current.": session form name: ".$_SESSION['form']);

/* Mostrar los datos del alumno en formato tabla html */
echo "<table align='center' border='1' bgcolor='#F0FFFF'>";
echo "<tr>";
echo "<th>Foto</th>";
echo "<th>Alumno</th>";
echo "</tr>";

echo "<tr>";

$query  = "SELECT * FROM ".$table." WHERE id='".$_GET['id']."'";
$result = mysql_query($query, $link)
             or die("Error en SELECT alumno ".mysql_error());

$row = mysql_fetch_array($result, MYSQL_ASSOC); 

/* Llamar por método GET al script que visualiza la imagen */ 
echo "<td><img src='".$script."?id=".$_GET['id']."'/></td>";

/* Mostrar nombre y apellidos en la tabla html a la derecha de la foto */
$alumno = $row['apellido1']." ".$row['apellido2'].", ".$row['nombre'];
echo "<td>".$alumno."</td>";
echo "</tr>";
echo "</table><br/><br/>";

/* Mostrar enlace para volver al listado de alumnos del curso */
//html_link_back("Volver al listado del curso");
echo "<a href='curso2.php?curso=".$row['curso']."'>";
echo "Volver al listado del curso</a>";

//echo "<a href='".$current."'>Seleccionar otro alumno</a>";
//echo "</td></tr>";

/* Liberar recursos cuando ya no son necesarios */
mysql_free_result($result);
mysql_close($link);

close_html_tags();
?>
