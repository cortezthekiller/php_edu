<?
session_start();
include("/var/seguridad/db.inc.php");

$login_table = "users";

/* Conectamos con el servidor y comprobamos la conexión */
$link = mysql_connect($mysql_host, $mysql_user, $mysql_passwd)
        or die('Error en la conexión: '.mysql_error());

mysql_select_db($mysql_db, $link);

echo "<html>";
echo "<head><title>Comprobación login</title>";
echo "<body>";

if(isset($_POST['username'])) {
   $query  = "SELECT passwd,lastlogin FROM ".$login_table;
   $query .= " WHERE username = '".$_POST['username']."'";

   $result = mysql_query($query, $link);

   if(mysql_num_rows($result)) {   /* Si el usuario existe en la tabla */

      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      if(md5($_POST['passwd']) == $row['passwd']) {
         echo "Bienvenido, ".$_POST['username']."<br/>";
         echo "Fecha y hora de la última conexión: ".$row['lastlogin'];

         /* Actualizar el campo 'lastlogin' con el timestamp actual */
         $query  = "UPDATE ".$login_table." SET lastlogin=CURRENT_TIMESTAMP ";
         $query .= "WHERE username='".$_POST['username']."'";

         mysql_query($query, $link)
            or die("Error UPDATE ".mysql_error());
      } else {
         echo "Nombre de usuario o password incorrectos<br/>";

         /* Mostrar enlace de vuelta al formulario, cuyo */
         /* nombre se ha pasado como variable de sesión. */
         if(!empty($_SESSION['form'])) {
            echo "<a href='http://".$_SESSION['form']."'>";
            echo "Volver al formulario</a>";
            session_unset();   /* Liberar variable(s) de sesión */
         } else {
            echo "Necesita habilitar las cookies en su navegador</br>";
            exit(-1);
         }
      }
   } else {
      echo "Nombre de usuario o password incorrectos<br/>";

      if(!empty($_SESSION['form'])) {
         echo "<a href='http://".$_SESSION['form']."'>Volver al formulario</a>";
         session_unset();   /* Liberar variable(s) de sesión */
      } else {
         echo "Necesita habilitar las cookies en su navegador</br>";
         exit(-1);
      }
   }

   mysql_free_result($result);
   mysql_close($link);
}

echo "</body>";
echo "</html>";

?>
