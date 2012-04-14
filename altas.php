<?
session_start();

include("func.inc.php");

/* Pasamos el nombre del formulario como variable de sesión  */
/* ya que según php.net no es recomendable usar HTTP_REFERER */
$_SESSION['referer'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

$current = basename($_SERVER['SCRIPT_NAME']);

open_html_tags("Formulario altas alumnos");
echo_username();

debug_msg($current.": recibe sesión userid: ".$_SESSION['userid']);

/* Array de cursos a los que pertenecen los alumnos */
$cursos = array("1A", "1B", "2A", "2B");

echo "<h2 style='text-align: center;'>Registrar nuevo alumno</h2>";
echo "<form name='altas' enctype='multipart/form-data' method='post' action='altas2.php'>";

echo "<table align='center' border='1'>";

/* Primera fila (encabezado) de la tabla html */
echo "<tr style='background-color: #F0FFFF;'>";   
echo "<th>Alumno</th>";
echo "<th>DNI</th>";
echo "<th>e-mail</th>";
echo "<th>Foto</th>";
echo "<th>Fecha nacimiento</th>";
echo "<th>curso</th>";
echo "</tr>";

/* Segunda fila de la tabla html para datos del alumno. */
/* Mostrar 3 inputs (nombre y 2 apellidos) en una celda.º */
echo "<tr>";
echo "<td>";
echo "<input type='text' name='nombre'    maxlength='20' size='4'/>";
echo "<input type='text' name='apellido1' maxlength='20' size='4'/>, ";
echo "<input type='text' name='apellido2' maxlength='20' size='4'/>";
echo "</td>";

echo "<td><input type='text' name='dni'maxlength='9' size='4'/></td>";
echo "<td><input type='text' name='email' maxlength='40' size='12'/></td>";
echo "<input type='hidden' name='max_size' value='102400'/>";
echo "<td><input type='file' name='image' accept='image/jpeg' size='4'/></td>";

/* Tres desplegables (día, mes y año) en una celda */
echo "<td><select name='day'>";
for($i=1; $i<32; $i++) {
   echo "<option value='$i'>$i</option>";
}
echo "</select>";

echo "de&nbsp;<select name='month'>";
for($i=1; $i<13; $i++) {
   echo "<option value='$i'>$i</option>";
}
echo "</select>";

echo "de&nbsp;<select name='year'>";
for($i=1935; $i<2013; $i++) {
   echo "<option value='$i'>$i</option>";
}
echo "</select></td>";

echo "<td><select name='curso'>";
foreach($cursos as $curso) {
   echo "<option value='".$curso."'>".$curso."</option>";
}
echo "</select></td>";
echo "</tr>";

echo "</table><br/>";

echo "<table align='center'>";
echo "<tr>"; /* Última fila de la tabla para mostrar los botones */
echo "<td colspan='4' align='center'><input type='submit' name='enviar' value='Enviar'/>";
echo "&nbsp;&nbsp;<input type='reset' name='reset' value='Reset'/></td>";
echo "</tr>";

echo "</table>";
echo "</form>";

close_html_tags();
?>
