<?
session_start();
/* Dos cookies: una para el timestamp del último login, que expira   */
/* en un mes y otra para el username que expira al cerrar la sesión. */
setcookie('lastlogin', date("G:i-m/d/y"), 60*60*24*30+time());
if(isset($_POST['username'])) {
   setcookie('username', $_POST['username']);
}

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current        = basename($_SERVER['SCRIPT_NAME']);
$login_table    = "users";
$default_passwd = "d11bd343e5e07b00f8b607403b03e9aa";   /* Encriptada */

open_html_tags("Comprobación login");

if(($_POST['username'])) {
   $query  = "SELECT id,passwd FROM ".$login_table;
   $query .= " WHERE username = '".$_POST['username']."'";

   $result = mysql_query($query, $link);

   if(mysql_num_rows($result)) {   /* Si el usuario existe en la tabla */

      $row = mysql_fetch_array($result, MYSQL_ASSOC);

      if(md5($_POST['passwd']) == $row['passwd']) {

//         echo_username();  /* Usa la cookie 'username' */

         /* Propagar el id del usuario como variable de sesión */
         $_SESSION['userid'] = $row['id'];

         debug_msg($current.": propagando user id: ".$_SESSION['userid']);

         /* La interfaz varía dependiendo de si es el primer login del     */
         /* usuario en la aplicación (todavía tiene password por defecto). */

         if($row['passwd'] != $default_passwd) {   

            /* No es la primera vez que se conecta */
            echo_username();  /* Usa la cookie 'username' */

            if(isset($_COOKIE['lastlogin'])) {

               echo "Hora y fecha última conexión: ".$_COOKIE['lastlogin'];

            } else {

               echo "¿Cookies deshabilitadas?";
            }

            echo "<br/><br/>";

            /* Interfaz principal :      */
            /*  - Cambiar contraseña     */
            /*  - Cerrar sesión          */
            include("interface.inc.php"); 

         } else {   /* Default password */

            /* La 1ª vez todavía no podemos ver el username con la cookie */
            echo "Usuario conectado: ".$_POST['username']."<br/>";

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
