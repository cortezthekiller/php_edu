<?
include("/var/seguridad/db.inc.php");

$fusers   = "users.txt";   /* Archivo de texto con credenciales de usuarios */
$falumnos = "alumnos.txt"; /* Archivo de texto con los datos de los alumnos */
$avatar   = "default.bin"; /* Blob para la foto por defecto de los perfiles */
$tusers   = "users";       /* Tabla usuarios (profesores) */
$talumnos = "alumnos";     /* Tabla principal */
$tables   = array("matematicas","historia","tecnologia"); /* Child tables */

/* Cargar datos de la tabla users desde un archivo de texto */
$query  = "LOAD DATA LOCAL INFILE '".$fusers."'";
$query .= " REPLACE INTO TABLE ".$tusers;
$query .= " FIELDS TERMINATED BY ','";
$query .= " LINES TERMINATED BY '\n' ";

mysql_query($query, $link) or die("Error LOAD DATA: ".$mysql_error($link));

/* Cargar datos de la tabla alumnos desde un archivo de texto */
$query  = "LOAD DATA LOCAL INFILE '".$falumnos."'";
$query .= " REPLACE INTO TABLE ".$talumnos;
$query .= " FIELDS TERMINATED BY ','";
$query .= " LINES TERMINATED BY '\n' ";

mysql_query($query, $link) or die("Error LOAD DATA: ".$mysql_error($link));

/* Añadir a cada perfil de alumno un avatar por defecto */
$result = mysql_query("SELECT contador FROM ".$talumnos, $link)
             or die ("Error en SELECT ".mysql_error());

/* Ruta completa del archivo con el blob para el avatar por defecto.      */
/* Señalar que el archivo binario se ha generado con SELECT INTO DUMPFILE */
$avatar = dirname(__FILE__)."/".$avatar;

while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $query  = "UPDATE ".$talumnos." SET foto=LOAD_FILE('".$avatar."') ";
   $query .= "WHERE contador=".$fila['contador'];

   mysql_query($query, $link)
      or die("Error UPDATE blob ".mysql_error());
}

/* Liberar recursos una vez utilizados */
mysql_free_result($result);

/* Una vez cargados los datos en la tabla principal, procedemos a */
/* insertar los correspondientes registros en las tablas de las   */
/* asignaturas (vinculadas por medio de DNI como clave foránea).  */

$query  = "SELECT DNI FROM ".$talumnos;
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
