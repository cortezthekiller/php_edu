<? 
include("/var/seguridad/db.inc.php");

$main_table = "alumnos";   /* Tabla principal */
/* Array con los nombres de las tablas vinculadas (asignaturas) */
$tables = array("matematicas","historia","tecnologia"); 

/* Conectamos con el servidor y comprobamos la conexi�n */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die('Error en la conexi�n: '.mysql_error());

/* Si la base de datos no existe, la creamos.           */ 
$query  = "CREATE DATABASE IF NOT EXISTS ".$mysql_db;
$query .= " DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
mysql_query($query) or die("Error CREATE DATABASE: ".mysql_error());

echo "Base de datos ".$mysql_db." creada con �xito (o ya exist�a)<br/>";

/* Seleccionamos la base de datos en cuesti�n */
mysql_select_db($mysql_db, $link);

/* Creaci�n de tablas. Utilizamos IF NOT EXISTS para que */
/* no de error en caso de llamar varias veces al script. */

/* Creaci�n de la tabla principal */
$query  = "CREATE TABLE IF NOT EXISTS ".$main_table;
$query .= "( ";
$query .= "contador TINYINT(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,";
$query .= "DNI VARCHAR(9) NOT NULL, ";
$query .= "nombre VARCHAR(20), ";
$query .= "apellido1 VARCHAR(20), ";
$query .= "apellido2 VARCHAR(20), ";
$query .= "nacimiento DATE, ";
$query .= "email VARCHAR(40) NOT NULL DEFAULT 'name@example.com', ";
$query .= "foto BLOB NOT NULL, ";
$query .= "curso ENUM('1A', '1B', '2A', '2B'),";
$query .= "PRIMARY KEY(DNI),";
$query .= "UNIQUE (contador)";
$query .= ") ENGINE=InnoDB";

mysql_query($query,$link) 
   or die("Error CREATE TABLE: ".$main_table.mysql_error());
echo "Tabla ".$main_table." creada con �xito (o ya exist�a)<br/>";

/* Creaci�n de las tablas vinculadas para las asignaturas (m�dulos) */
/* Utilizar�n el DNI de la tabla principal como clave for�nea.      */
/* Definimos DNI como �ndice �nico porque un alumno no puede tener  */
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

   echo "Tabla ".$asignatura." creada con �xito (o ya exist�a)</br>";
}

/* Cerramos la conexi�n con el servidor */
mysql_close($link);
?>
