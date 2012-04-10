<?
session_start();
/* Pasamos el nombre del formulario como variable de sesión  */
/* ya que según php.net no es recomendable usar HTTP_REFERER */
$_SESSION['form'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
?>

<html>
<head><title>Login</title></head>
<body>
<form name="login" method="POST" action="login2.php">
Usuario&nbsp;&nbsp;&nbsp;<input type="text" name="username"/><br/>
Password&nbsp;<input type="password" name="passwd"/><br/><br/>
<input type="submit" value="Enviar"/>
</form>
</body>
</html>
