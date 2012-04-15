<?
session_start();
include("func.inc.php");

$action_script = "forget2.php";

open_html_tags("Contraseña olvidada");

echo "<form name='forget' method='post' action='".$action_script."'>";
echo "Escriba su correo electrónico:&nbsp;";
echo "<input type='text' name='email' size='10' maxlength='40'/>";
echo "<input type='submit' value='Enviar'/>";
echo "</form>";

close_html_tags();
?>
