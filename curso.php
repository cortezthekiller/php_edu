<?
session_start();

include("func.inc.php");

$cursos = array("1A", "1B", "2A", "2B");
$style  = "style='text-align: center;'";

/* Pasar el nombre del formulario como variable de sesión */
$_SESSION['referer'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

open_html_tags("Seleccionar alumnos curso");
echo_username();

echo "<h2 ".$style.">Mostrar perfiles de alumnos</h2></br>";

echo "<form name='cursos' method='get' action='curso2.php'>";
echo "<table style='margin: auto;'/>";
echo "<tr><td>";
echo "Curso: &nbsp;<select name='curso'>";

/* Mostramos desplegable con los cursos */
foreach($cursos as $curso) {
      echo "<option value='".$curso."'>".$curso."</option>";
}

echo "</select>";
echo "&nbsp;&nbsp;<input type='submit' value='OK'/>";
echo "</td></tr></table>";
echo "</form>";

close_html_tags();
?>
