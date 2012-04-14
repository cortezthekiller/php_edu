<?
session_start();

include_once("/var/seguridad/db.inc.php");
include("func.inc.php");

/* Array de cursos a los que pertenecen los alumnos */
$cursos  = array("1A", "1B", "2A", "2B");
$table   = "alumnos";
$script  = "alumno.php";
$current = basename($_SERVER['SCRIPT_NAME']);

open_html_tags("Mostrar Perfil Alumno");
echo_username();

echo "<h2 style='text-align: center;'>Perfil alumno</h2>";

/* Formulario que contiene una tabla con los campos de datos del alumno */
echo "<form name='profile' enctype='multipart/form-data' method='post' action='update.php'>";

/* Mostrar los datos del alumno en formato tabla html */
echo "<table id='fixed'>";
echo "<tr>";
echo "<th width='12%'>Foto</th>";
echo "<th width='24%'>Alumno</th>";
echo "<th width='14%'>DNI</th>";
echo "<th width='20%'>Fecha nacimiento</th>";
echo "<th width='12%'>e-mail</th>";
echo "<th width='4%'>Curso</th>";
echo "<th width='14%'></th>";
echo "</tr>";

echo "<tr>";

/* Consultar los datos del alumno en la base de datos */
$query  = "SELECT * FROM ".$table." WHERE id='".$_GET['id']."'";
$result = mysql_query($query, $link)
             or die("Error en SELECT alumno ".mysql_error());

$row = mysql_fetch_array($result, MYSQL_ASSOC); 

/* Llamar por método GET al script que visualiza la imagen */ 
echo "<td>";
echo "<img src='".$script."?id=".$_GET['id']."' alt='avatar'/>";
echo "<input type='file' name='image' accept='image/jpeg' size='4'/>";
echo "</td>";

/* Pasaremos el id del alumno en un input hidden */
echo "<input type='hidden' name='id' value='".$_GET['id']."'/>";

/* 3 inputs para nombre y apellidos del alumno */
$input  = "<input type='text' name='apellido1' maxlength='20' size='4'";
$input .= " value='".$row['apellido1']."'/>";
$input .= "<input type='text' name='apellido2' maxlength='20' size='4'";
$input .= " value='".$row['apellido2']."'/>, ";
$input .= "<input type='text' name='nombre'    maxlength='20' size='4'";
$input .= " value='".$row['nombre']."'/>";

/* Mostrar los 3 input en una celda */
echo "<td>".$input."</td>";

/* Mostrar un input con el DNI del alumno */
$input  = "<input type='text' name='DNI' maxlength='9' size='4'";
$input .= " value='".$row['DNI']."'/>";
echo "<td>".$input."</td>";

/* 3 selects (desplegables) para día, mes y año. Como opciones por */
/* defecto mostrarán el día mes y año de nacimiento de cada alumno */
$date = date_parse($row['nacimiento']);

$select_day = "<select name='day'>"; 

for($i=1; $i<32; $i++) {

   if($i == $date['day']) {

      $select_day .= "<option value='$i' selected='yes'>$i</option>";

   } else { 

      $select_day .= "<option value='$i'>$i</option>";
   }
}

$select_day .= "</select>";

$select_month = "<select name='month'>"; 

for($i=1; $i<13; $i++) {

   if($i == $date['month']) {

      $select_month .= "<option value='$i' selected='yes'>$i</option>";

   } else { 

      $select_month .= "<option value='$i'>$i</option>";
   }
}

$select_month .= "</select>";

$select_year = "<select name='year'>"; 

for($i=1935; $i<2013; $i++) {

   if($i == $date['year']) {

      $select_year .= "<option value='$i' selected='yes'>$i</option>";

   } else { 

      $select_year .= "<option value='$i'>$i</option>";
   }
}

$select_year .= "</select>";

/* Mostrar los 3 selects en una celda de la tabla */
echo "<td>".$select_day." de ".$select_month." de ".$select_year."</td>";

/* Mostrar un input con el email del alumno */
$input  = "<input type='text' name='email' maxlength='40' size='12'";
$input .= " value='".$row['email']."'/>";
echo "<td>".$input."</td>";

/* Mostrar un desplegable para el curso */
$select = "<select name='curso'>";

/* Como opción por defecto aparecera el curso actual del alumno */
foreach($cursos as $curso) {

   if($curso == $row['curso']) {

      $select .= "<option value='".$curso."' selected='1'>".$curso."</option>";

   } else {

      $select .= "<option value='".$curso."'>".$curso."</option>";
   }
}

$select .= "</select>";
echo "<td>".$select."</td>";

echo "<td>";
echo "<input type='submit' name='update' value='Actualizar'/>";
echo "<input type='submit' name='drop' value='Dar de baja'/>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form><br/><br/>";

/* Mostrar enlace para volver al listado de alumnos del curso */
echo "<a href='curso2.php?curso=".$row['curso']."'>";
echo "Volver al listado del curso</a>";


/* Liberar recursos cuando ya no son necesarios */
mysql_free_result($result);
mysql_close($link);

close_html_tags();
?>
