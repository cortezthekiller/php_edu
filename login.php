<?
/* Puede que se llame a este script desde algún punto de la aplicación   */
/* porque el usuario haya decidido finalizar la sesión. Por eso cerramos */
/* la sesión (por si hubiera alguna abierta) antes de iniciar una nueva. */
if(isset($_SESSION['CREATED'])) {
   session_unset();
   session_destroy();
}
session_start();
$_SESSION['CREATED'] = 1;  /* Para evitar warning al destruir sesión */

include("func.inc.php");

/* Pasamos el nombre del formulario como variable de sesión  */
/* ya que según php.net no es recomendable usar HTTP_REFERER */
$_SESSION['referer'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

open_html_tags("Login de usuarios");

echo "<form name='login' method='post' action='login2.php'>";
echo "Usuario&nbsp;&nbsp;&nbsp;";
echo "<input type='text' name='username' size='5' maxlength='20'/><br/>";
echo "Password&nbsp;";
echo "<input type='password' name='passwd' size='5'/><br/><br/>";
echo "<input type='submit' value='Enviar'/>";
echo "</form><br/>";
echo "<a href='forget.php'>¿Olvidó su contraseña?</a>";

close_html_tags();
?>
