<?
session_start();
include("func.inc.php");

/* Los tags html tiene que escribirlos el script que llama al include */
open_html_tags("Cambiar contraseña");
echo_username();
include("newpass.inc.php");
close_html_tags();
?>
