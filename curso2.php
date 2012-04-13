<?
session_start();

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$style  = "style='text-align: center;'";
$table  = "alumnos";
$perfil = "profile.php";
$cont   = 0;   /* Contador de alumnos por curso */

/* Seleccionar todos los alumnos del curso solicitado */
$query  = "SELECT * from ".$table." WHERE curso='".$_GET['curso']."' ";
$query .= "ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";

$result = mysql_query($query, $link)
             or die ("Error SELECT curso: ".mysql_error());

open_html_tags("Listado alumnos");

echo "<h2 ".$style.">Curso ".$_GET['curso']."</h2></br>";

/* Presentar resultados en una tabla, una fila por alumno */
echo "<table frame='box' style='margin: auto;'>";
echo "<tr>";
echo "<th>nº</th>";
echo "<th>Alumno</th>";
echo "<th></th>";
echo "</tr>";

while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

   $cont++; /* Incrementar contador alumnos por curso */

   echo "<tr>";
   echo "<td>".$cont."</td>";

   /* Componer celda con el nombre y apellidos del alumno */
   $alumno = $row['apellido1']." ".$row['apellido2'].", ".$row['nombre'];

   echo "<td>".$alumno."</td>";
   echo "<td><a href='".$perfil."?id=".$row['contador']."'>Ver perfil</a></td>";
   echo "</tr>";
}

echo "</table></tr></tr>";

echo "session form name: ".$_SESSION['form'];
echo "<div ".$style.">";
html_link_back("Seleccionar otro curso");
echo "</div>";

/* Pasaremos a 'profile.php' el nombre de este script para volver a él */
$_SESSION['form'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

html_close_tags();

mysql_free_result($result);
mysql_close($link);
?>
