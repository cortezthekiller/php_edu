<?
session_start();
include("/var/seguridad/db.inc.php");
include("func.inc.php");

$login_table = "users";

open_html_tags("Comprobación login");

if(($_POST['username'])) {
   $query  = "SELECT id,passwd,lastlogin,conns FROM ".$login_table;
   $query .= " WHERE username = '".$_POST['username']."'";

   $result = mysql_query($query, $link);

   if(mysql_num_rows($result)) {   /* Si el usuario existe en la tabla */

      $row = mysql_fetch_array($result, MYSQL_ASSOC);

      if(md5($_POST['passwd']) == $row['passwd']) {
         echo "Hola, ".$_POST['username']."<br/>";

         /* Propagar el id del usuario como variable de sesión */
         $_SESSION['userid'] = $row['id'];
         echo "login2.php propagando id: ".$_SESSION['userid'];

         /* Actualizar el campo 'lastlogin' con el timestamp actual */
         $query  = "UPDATE ".$login_table." SET lastlogin=CURRENT_TIMESTAMP ";
         $query .= "WHERE username='".$_POST['username']."'";
 
         mysql_query($query, $link)
            or die("Error UPDATE ".mysql_error());

         /* La interfaz varía dependiendo de si es el  */
         /* primer login del usuario en la aplicación. */

         if($row['conns']) {   /* No es la primera vez que se conecta */

            echo "Fecha y hora de la última conexión: ".$row['lastlogin'];
            echo "<br/><br/>";

            /* Interfaz principal :      */
            /*  - Cambiar contraseña     */
            /*  - Cerrar sesión          */
            include("interface.inc.php"); 

            /* Incrementar el campo correspondiente al contador de logins */
            $query  = "UPDATE ".$login_table." SET conns=conns+1 ";
            $query .= "WHERE username='".$_POST['username']."'";

            mysql_query($query, $link)
               or die("Error UPDATE ".mysql_error());

         } else {

            echo "Es la primera vez que entras al sistema.<br/>";
            echo "Debes cambiar la contraseña por motivos de seguridad ";

            include("newpass.inc.php"); /* script cambio contraseña */
         }
      } else {
         echo "Nombre de usuario o password incorrecto<br/>";
         html_link_back("Volver al formulario de login");
      }
   } else {
      echo "Nombre de usuario o password incorrecto<br/>";
      html_link_back("Volver al formulario de login");
   }

   mysql_free_result($result);
   mysql_close($link);

   close_html_tags();

} else {
   echo "Por favor, introduzca un nombre de usuario<br/>";
   html_link_back("Volver");
}

?>
