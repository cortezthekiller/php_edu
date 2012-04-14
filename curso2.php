<?
session_start();

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current = basename($_SERVER['SCRIPT_NAME']);
$style   = "style='text-align: center;'";
$table   = "alumnos";
$profile = "profile.php";
$cont    = 0;   /* Contador de alumnos por curso */

/* Seleccionar todos los alumnos del curso solicitado */
$query  = "SELECT * from ".$table." WHERE curso='".$_GET['curso']."' ";
$query .= "ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";

$result = mysql_query($query, $link)
             or die ("Error SELECT curso: ".mysql_error());

open_html_tags("Listado alumnos");
echo_username();
debug_msg($current.": session form name: ".$_SESSION['referer']);

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
   echo "<td><a href='".$profile."?id=".$row['id']."'>Ver perfil</a></td>";
   echo "</tr>";
}

echo "</table></tr></tr>";

echo "<div ".$style.">";
html_link_back("Seleccionar otro curso");
echo "<br/>";
echo "<a href='interface.php'>Volver menú principal</a>";
echo "</div>";


close_html_tags();

mysql_free_result($result);
mysql_close($link);
?>
