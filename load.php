<?
include("/var/seguridad/db.inc.php");

$file   = "datos.txt";   /* Archivo de texto con los datos de los alumnos */
$table  = "alumnos";     /* Tabla principal */
$tables = array("matematicas","historia","tecnologia"); /* Tablas vinculadas */


/* Conectamos con el servidor y comprobamos la conexión */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die('Error en la conexión: '.mysql_error());

mysql_select_db($mysql_db, $link);

$query  = "LOAD DATA LOCAL INFILE '".$file."'";
$query .= " REPLACE INTO TABLE ".$table;
$query .= " FIELDS TERMINATED BY ','";
$query .= " LINES TERMINATED BY '\n' ";

mysql_query($query, $link) or die("Error LOAD DATA: ".$mysql_error($link));

/* Una vez cargados los datos en la tabla principal, procedemos a */
/* insertar los correspondientes registros en las tablas de las   */
/* asignaturas (vinculadas por medio de DNI como clave foránea).  */

$query  = "SELECT DNI FROM ".$table;
$result = mysql_query($query, $link);

while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {

   foreach($tables as $tablename) {
      $insert = "INSERT INTO ".$tablename." (DNI) VALUES ('".$fila['DNI']."')";
      mysql_query($insert, $link) 
         or die("Error en INSERT: ".mysql_error($link));
   }
}

/* Liberar recursos y cerrar conexión con servidor */
mysql_free_result($result);
mysql_close($link);
?>
