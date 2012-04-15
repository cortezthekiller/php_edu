<?
session_start();

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current   = basename($_SERVER['SCRIPT_NAME']);
$table     = "alumnos";
$materias  = array("matematicas", "historia", "tecnologia"); 
$fields    = array("id", "DNI", "apellido1", "apellido2", "nombre");
$key       = "DNI";   /* Clave que vincula tablas de materias con alumnos */
$profile   = "profile.php";
$genpdf    = "genpdf.php";
$interface = "interface.php";
$cont      = 0;   /* Contador de alumnos por curso */

open_html_tags("Listado alumnos");
echo_username();
debug_msg($current.": session form name: ".$_SESSION['referer']);

echo "<h2>Curso ".$_GET['curso']."</h2><br/>";

/* Presentar resultados en una tabla, una fila por alumno.            */
/* La tabla contiene también como columnas la nota en cada asignatura */
echo "<table>";
echo "<tr>";
echo "<th>nº</th>";
echo "<th>Alumno</th>";
echo "<th>Perfil</th>"; /* Columna para el link mostrar perfil alumno */

foreach($materias as $materia) {

   echo "<th>".$materia."</th>";
}

echo "<th>Notas</th>"; /* Columna para botón actualizar calificaciones */
echo "</tr>";

/* Construimos una query a varias tablas.            */
/* Es bastante larga, exige varias líneas de código. */
$query  = "SELECT ".$table.".".$fields[0].",";
$query .= $table.".".$fields[1].",";
$query .= $table.".".$fields[2].",";
$query .= $table.".".$fields[3].",";
$query .= $table.".".$fields[4].",";

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

   /* $row[0]=id, $row[1]=DNI, $row[2]=apellido1, $row[3]=apellido2          */
   /* $row[4]=nombre, $row[5]=nota-mate, $row[6]=nota-hist, $row[7]=nota-tec */

   /* Componer celda con el nombre y apellidos del alumno */
   $alumno = $row[2]." ".$row[3].", ".$row[4];

   echo "<td>".$alumno."</td>";
   echo "<td><a href='".$profile."?id=".$row[0]."'>Ver perfil</a></td>";

   /* Mostrar las celdas para las notas de cada materia.   */
   /* Formulario con un input de tipo texto por cada nota. */  
   echo "<form name='grades' method='post' action='grades.php'>";

   /* Pasamos oculto el DNI y el curso del alumno.                   */
   /* El curso lo necesitamos para el enlace de vuelta a este script */
   echo "<input type='hidden' name='DNI'  value='".$row[1]."'/>";
   echo "<input type='hidden' name='curso' value='".$_GET['curso']."'/>";

   for($i=5, $j=0; $i<sizeof($row); $i++,$j++) {
      $input  = "<input type='text' size='2' ";
      $input .= "name='".$materias[$j]."' value='".$row[$i]."'/>";
      echo "<td>".$input."</td>";
   }

   /* Botón del formulario para actualizar notas alumno */
   echo "<td><input type='submit' name='update' value='Guardar'/></td>";
   echo "</form>";

   echo "</tr>";
}

echo "</table><br/><br/>";

echo "<div>";
html_link_back("Seleccionar otro curso");
echo "<br/><br/>";
echo "<a href='".$genpdf."?curso=".$_GET['curso']."'>";
echo "<button type='button'>Generar PDF</button></a>";
echo "<a href='".$interface."'>";
echo "<button type='button'>Volver menú principal</button></a>";
echo "</div>";

close_html_tags();

mysql_free_result($result);
mysql_close($link);
?>
