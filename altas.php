<?
session_start();
/* Pasamos el nombre del formulario como variable de sesión  */
/* ya que según php.net no es recomendable usar HTTP_REFERER */
$_SESSION['form'] = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
?>

<html>
<head>
<title>Formulario ejercicio 40</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<h2>Datos del alumno</h2>
<p>
<form name="altas" enctype="multipart/form-data" method="POST" action="altas2.php">
Nombre:<br/><input type="text" name="nombre"/><br/><br/>
Primer apellido:<br/><input type="text" name="apellido1"/><br/><br/>
Segundo apellido:</br><input type="text" name="apellido2"/><br/><br/>
DNI:</br><input type="text" name="dni"/><br/><br/>
e-mail:</br><input type="text" name="email"/><br/><br/>
<input type="hidden" name="max_size" value="102400"/>
Foto:</br><input type="file" name="image" accept="image/jpeg"/><br/><br/>
Fecha de nacimiento:</br>
Día:<select name="day">
<? 
for($i=1; $i<32; $i++) {
   echo "<option value='$i'>$i</option>";
}
?>
</select>

mes: <select name="month">
<? 
for($i=1; $i<13; $i++) {
   echo "<option value='$i'>$i</option>";
}
?>
</select>

año: <select name="year">
<? 
for($i=1935; $i<2013; $i++) {
   echo "<option value='$i'>$i</option>";
}
?>
</select><br/><br/>

Curso: <select name="curso">
<?
$cursos = array("1A", "1B", "2A", "2B");
foreach($cursos as $curso) {
   echo "<option value='".$curso."'>".$curso."</option>";
}
?>
</select><br/><br/>

<input type="submit" name="enviar" value="Enviar"/>
<input type="reset" name="reset" value="Reset"/>

</form>
</p>
</body>
</html>
