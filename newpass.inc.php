<?
session_start();
$action = "newpass.php";

/* Propagar el nombre del formulario como variable de sesión */
$_SESSION['referer'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

/* Este archivo se llamará siempre a través de include(), por lo que será */
/* el archivo llamante el que incluya los tags html de inicio y cierre.   */
echo "<h2>Cambiar contraseña</h2><br/><br/>";
echo "<form name='change' method='post' action='".$action."'>";
echo "Nueva contraseña:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type='password' name='newpass1'/><br/>";
echo "Confirmar contraseña:&nbsp;";
echo "<input type='password' name='newpass2'/><br/>";
echo "<input type='submit' name='enviar' value='Enviar'/>";
echo "</form>";
?>
