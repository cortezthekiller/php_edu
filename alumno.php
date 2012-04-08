<?
include_once("/var/seguridad/db.inc.php"); 

$table = "alumnos";

/* Conectarse al servidor de base de datos */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die("Error en la conexión: ".mysql_error());

mysql_select_db ($mysql_db, $link);

/* En la consulta seleccionamos el campo 'foto'.            */
/* El resto de campos los pasamos como variables de sesión. */
$query  = "SELECT * FROM ".$table." WHERE DNI='".$_GET['id']."'";
$result = mysql_query($query, $link);
$row    = mysql_fetch_array($result); 
$data   = $row['foto'];

header('Content-type: image/jpeg');
echo $data;

mysql_free_result($result);
mysql_close($link); 
?>
