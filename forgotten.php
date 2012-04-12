<?
session_start();
include("func.inc.php");

open_html_tags("Contraseña olvidada");

echo "<form name='forget' method='post' action='forgotten2.php'>";
echo "Escriba su correo electrónico:&nbsp;<input type='text' name='email'>";
echo "<input type='submit' value='Enviar'/>";
echo "</form>";

close_html_tags();
?>
