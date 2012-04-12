<?
session_start();

include("func.inc.php");

/* Pasamos el nombre del formulario como variable de sesión  */
/* ya que según php.net no es recomendable usar HTTP_REFERER */
$_SESSION['form'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

open_html_tags("Formulario altas alumnos");

echo "altas.php - recibe sesión userid: ".$_SESSION['userid']."<br/>";

/* Array de cursos a los que pertenecen los alumnos */
$cursos = array("1A", "1B", "2A", "2B");

echo "<h2 style='text-align: center;'>Registrar nuevo alumno</h2>";
echo "<form name='altas' enctype='multipart/form-data' method='post' action='altas2.php'>";

/* Dividiremos la presentación en tres tablas por cuestión de estética */
echo "<table align='center' border='1'>";

/* Primera fila (encabezado) de la tabla html */
echo "<tr style='background-color: #F0FFFF;'>";   
echo "<th>Nombre</th>";
echo "<th>Primer apellido</th>";
echo "<th>Segundo apellido</th>";
echo "<th>DNI</th>";
echo "</tr>";

echo "<tr>";
echo "<td><input type='text' name='nombre'/></td>";
echo "<td><input type='text' name='apellido1'/></td>";
echo "<td><input type='text' name='apellido2'/></td>";
echo "<td><input type='text' name='dni'/></td>";
echo "</tr>";
echo "</table><br/>";

/* Segunda tabla */
echo "<table align='center' border='1' bgcolor='#F0FFFF'>";
echo "<tr>";   /* Primera fila (encabezado) de la tabla html */
echo "<th>e-mail</th>";
echo "<th>Foto</th>";
echo "<th>Fecha nacimiento</th>";
echo "<th>curso</th>";
echo "</tr>";

echo "<tr>";
echo "<td><input type='text' name='email'/></td>";
echo "<input type='hidden' name='max_size' value='102400'/>";
echo "<td><input type='file' name='image' accept='image/jpeg'/></td>";
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
