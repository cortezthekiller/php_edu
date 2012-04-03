<?
include_once("/var/seguridad/mysql.inc.php"); 
$table = "images";

/* Conectarse al servidor de base de datos */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die("Error en la conexión: ".mysql_error());

mysql_select_db ($mysql_db, $link);

/* En la consulta seleccionamos sólo el campo 'image' */
$query = "SELECT * FROM ".$table." WHERE id=".$_GET['id'];
$result = mysql_query($query, $link);
$row    = mysql_fetch_array($result); 
$data   = $row['image'];
header('Content-type: image/jpeg');
echo $data;
mysql_free_result($result);
mysql_close($link); 
?>
