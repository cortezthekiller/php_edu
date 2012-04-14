<?
session_start();

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current   = basename($_SERVER['SCRIPT_NAME']);
$style     = "style='text-align: center;'";
$table     = "alumnos";
$materias  = array("matematicas", "historia", "tecnologia"); 
$fields    = array("id", "apellido1", "apellido2", "nombre");
$key       = "DNI";   /* Clave que vincula tablas de materias con alumnos */
$profile   = "profile.php";
$interface = "interface.php";
$cont      = 0;   /* Contador de alumnos por curso */

/* Seleccionar todos los alumnos del curso solicitado */
//$query  = "SELECT * FROM ".$table." WHERE curso='".$_GET['curso']."' ";
//$query .= "ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";

//$result = mysql_query($query, $link)
             //or die ("Error SELECT curso: ".mysql_error());

open_html_tags("Listado alumnos");
echo_username();
debug_msg($current.": session form name: ".$_SESSION['referer']);

echo "<h2 ".$style.">Curso ".$_GET['curso']."</h2><br/>";

/* Presentar resultados en una tabla, una fila por alumno.            */
/* La tabla contiene también como columnas la nota en cada asignatura */
echo "<table>";
echo "<tr>";
echo "<th>nº</th>";
echo "<th>Alumno</th>";
echo "<th></th>"; /* Columna para el link mostrar perfil alumno */

foreach($materias as $materia) {

   echo "<th>".$materia."</th>";
}

echo "<th></th>"; /* Columna para botón actualizar calificaciones */
echo "</tr>";

/* Construimos una query a varias tablas.            */
/* Es bastante larga, exige varias líneas de código. */
$query  = "SELECT ".$table.".".$fields[0].",";
$query .= $table.".".$fields[1].",";
$query .= $table.".".$fields[2].",";
$query .= $table.".".$fields[3].",";

foreach($materias as $index=>$materia) {

   $query .= $materia.".nota";

   /* Después del último campo no hay que añadir la coma */
   if($index < sizeof($materias)-1)
      $query .= ",";
}

$query .= " FROM ".$table.",";

foreach($materias as $index=>$materia) {

   $query .= $materia;

   /* Después del último campo no hay que añadir la coma */
   if($index < sizeof($materias)-1)
      $query .= ",";
}

/* Seleccionamos sólo alumnos del curso solicitado */
$query .= " WHERE ".$table.".curso='".$_GET['curso']."' AND ";

/* El DNI es la clave foránea que vincula las asignaturas con el alumno */
foreach($materias as $index=>$materia) {
   $query .= $table.".".$key."=".$materia.".".$key;

   /* Después de la última tabla no hay que añadir AND */
   if($index < sizeof($materias)-1)
      $query .= " AND ";
}

/* Por último, ordenar los alumnos alfabéticamente */
$query .= " ORDER BY ".$table.".apellido1 ASC, ";
$query .= $table.".apellido2 ASC, ".$table.".nombre ASC";

/* Query muy larga, imprimirla para debug */
debug_msg($current.": ".$query);

$result = mysql_query($query, $link)
      or die("Error query SELECT: ".mysql_error($link));

/* Curioso: no podemos utilizar un array asociativo para el fecth */
/* porque hay varios campos que coincidirían en nombre ('nota').  */
/* Usamos un array escalar con MYSQL_NUM en vez de MYSQL_ASSOC */

while($row = mysql_fetch_array($result, MYSQL_NUM)) {

   $cont++; /* Incrementar contador alumnos por curso */

   echo "<tr>";    /* Una fila por cada alumno */
   echo "<td>".$cont."</td>";

   /* Componer celda con el nombre y apellidos del alumno */
   $alumno = $row[1]." ".$row[2].", ".$row[3];

   echo "<td>".$alumno."</td>";
   echo "<td><a href='".$profile."?id=".$row[0]."'>Ver perfil</a></td>";

   /* Mostrar las celdas para las notas de cada materia */
   for($i=4; $i<sizeof($row); $i++) {
      echo "<td>".$row[$i]."</td>";
   }

   /* Botón para actualizar notas alumno */
   echo "<td><a href='#'>Actualizar</a></td>";
 

   echo "</tr>";
}

echo "</table><br/><br/>";

echo "<div ".$style.">";
html_link_back("Seleccionar otro curso");
echo "<br/><br/>";
echo "<a href='".$interface."'>";
echo "<button type='button'>Volver menú principal</button></a>";
echo "</div>";

close_html_tags();

mysql_free_result($result);
mysql_close($link);
?>
