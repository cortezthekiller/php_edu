<?
$change  = "change.php";
$script1 = "altas.php";
$script2 = "curso.php";
$exit    = "login.php";

/* Este archivo se llamará siempre a través de include(), por lo que será */
/* el archivo llamante el que incluya los tags html de inicio y cierre.   */
echo "<div style='text-align: center'>";
echo "<h2>Menú principal</h2><br/>";
echo "<a href='".$change."'>";
echo "<button type='button'>Cambiar contraseña</button></a>&nbsp;&nbsp;";

echo "<a href='".$script1."'>";
echo "<button type='button'>Dar de alta/baja alumnos</button></a>&nbsp;&nbsp;";

echo "<a href='".$script2."'>";
echo "<button type='button'>Ver perfiles alumnos</button></a>&nbsp;&nbsp;";

echo "<a href='".$exit."'>";
echo "<button type='button'>Cerrar sesión</button></a><br/><br/>";
echo "</div>";

?>
