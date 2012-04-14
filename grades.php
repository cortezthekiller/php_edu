<?
include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current   = basename($_SERVER['SCRIPT_NAME']);
$tables = array("matematicas","historia","tecnologia");

$query  = "UPDATE ".$tables[0].",".$tables[1].",".$tables[2]." ";
$query .= " SET ".$tables[0].".nota=".$_POST['matematicas'].",";
$query .=         $tables[1].".nota=".$_POST['historia'].",";   
$query .=         $tables[2].".nota=".$_POST['tecnologia'];
$query .= " WHERE ".$tables[0].".DNI='".$_POST['DNI']."'"; 
$query .=   " AND ".$tables[1].".DNI='".$_POST['DNI']."'";
$query .=   " AND ".$tables[2].".DNI='".$_POST['DNI']."'";

open_html_tags("Actualizar notas");
debug_msg($current.": ".$query);

$num_rows = mysql_query($query, $link)
   or die("Error UPDATE: ".mysql_error());

debug_msg($current.": ".mysql_affected_rows()." rows affected after UPDATE");
echo "Notas guardadas en la base de datos<br/>";

/* Mostrar enlace para volver al listado de alumnos del curso */
echo "<a href='curso2.php?curso=".$_POST['curso']."'>";
echo "Volver al listado del curso</a>";

close_html_tags();

mysql_close($link);
?>
