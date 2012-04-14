<? 
include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current     = basename($_SERVER['SCRIPT_NAME']);
$login_table = "users";
$main_table  = "alumnos";   /* Tabla principal */
/* Array con los nombres de las tablas vinculadas (asignaturas) */
$tables      = array("matematicas", "historia", "tecnologia"); 

/* Array bidimensional para 2 triggers MySQL */
$triggers = array (
   0 => array("name" => "md5insert", "event" => "INSERT"),
   1 => array("name" => "md5update", "event" => "UPDATE"));

/* Conectamos con el servidor y comprobamos la conexión */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die('Error en la conexión: '.mysql_error());

/* Si la base de datos no existe, la creamos.           */ 
$query  = "CREATE DATABASE IF NOT EXISTS ".$mysql_db;
$query .= " DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
mysql_query($query) or die("Error CREATE DATABASE: ".mysql_error());

debug_msg($current.": Database ".$mysql_db." creada con éxito (o ya existía)");

/* Seleccionamos la base de datos en cuestión */
mysql_select_db($mysql_db, $link);

/* Creación de tablas. Utilizamos IF NOT EXISTS para que */
/* no de error en caso de llamar varias veces al script. */

/* Creación de la tabla principal */
$query  = "CREATE TABLE IF NOT EXISTS ".$main_table;
$query .= "( ";
$query .= "id TINYINT(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,";
$query .= "DNI VARCHAR(9) NOT NULL, ";
$query .= "nombre VARCHAR(20), ";
$query .= "apellido1 VARCHAR(20), ";
$query .= "apellido2 VARCHAR(20), ";
$query .= "nacimiento DATE, ";
$query .= "email VARCHAR(40) NOT NULL, ";
$query .= "foto BLOB NOT NULL, ";
$query .= "curso ENUM('1A', '1B', '2A', '2B'),";
$query .= "PRIMARY KEY(DNI),";
$query .= "UNIQUE (id),";
$query .= "UNIQUE (email)";
$query .= ") ENGINE=InnoDB";

mysql_query($query,$link) 
   or die("Error CREATE TABLE: ".$main_table.mysql_error());

debug_msg($current.": Tabla ".$main_table." creada con éxito (o ya existía)");

/* Creación de las tablas vinculadas para las asignaturas (módulos) */
/* Utilizarán el DNI de la tabla principal como clave foránea.      */
/* Definimos DNI como índice único porque un alumno no puede tener  */
/* varias notas en una asignatura.                                  */

foreach($tables as $asignatura) {
   $query  = "CREATE TABLE IF NOT EXISTS ".$asignatura;
   $query .= "( ";
   $query .= "DNI VARCHAR(9) NOT NULL, ";
   $query .= "nota TINYINT(2) UNSIGNED NOT NULL, ";
   $query .= "UNIQUE (DNI), ";
   $query .= "FOREIGN KEY(DNI) REFERENCES ".$main_table."(DNI) ";
   $query .= "ON DELETE CASCADE ON UPDATE CASCADE ";
   $query .= ") ENGINE=InnoDB";

   mysql_query($query, $link)
      or die("Error CREATE TABLE ".$asignatura.mysql_error());

debug_msg($current.": Tabla ".$asignatura." creada con éxito (o ya existía)");
}

/* Creamos la tabla para el login inicial de los usuarios (profesores) */
$query  = "CREATE TABLE IF NOT EXISTS ".$login_table;
$query .= "( ";
$query .= "id TINYINT(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,";
$query .= "username VARCHAR(20), ";
$query .= "email    VARCHAR(40) NOT NULL, ";
$query .= "passwd   VARCHAR(100) NOT NULL DEFAULT 'password00', ";
$query .= "UNIQUE(username),";
$query .= "UNIQUE(email),";
$query .= "PRIMARY KEY(id) ";
$query .= ") ENGINE=InnoDB";

mysql_query($query, $link)
   or die("Error CREATE TABLE ".$login_table.mysql_error());

debug_msg($current.": Tabla ".$login_table." creada con éxito (o ya existía)");

/* Importante: por seguridad, queremos que las passwords se guarden */
/* encriptadas con MD5 en la base de datos. Aprenderemos a utilizar */
/* triggers en MySQL, en este caso con los eventos INSERT.          */

foreach($triggers as $trigger) {

   /* Si ya existe un trigger con el mismo nombre, lo */
   /* borramos antes de crearlo para evitar errores.  */
   $query  = "DROP TRIGGER IF EXISTS ".$trigger['name'];
   mysql_query($query, $link);

   $query  = "CREATE TRIGGER ".$trigger['name'];
   $query .= " BEFORE ".$trigger['event']." ON ".$login_table;
   $query .= " FOR EACH ROW SET NEW.passwd = MD5(NEW.passwd)";

   mysql_query($query, $link)
      or die("Error CREATE TRIGGER ".$trigger.mysql_error());

   debug_msg($current.": Trigger ".$trigger['name']." creado con éxito");
}

/* Cerramos la conexión con el servidor */
mysql_close($link);
?>
