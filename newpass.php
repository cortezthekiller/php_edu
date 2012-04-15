<?
session_start();

include("/var/seguridad/db.inc.php");
include("func.inc.php");

$current = basename($_SERVER['SCRIPT_NAME']);
$table   = "users";
$default_passwd = "d11bd343e5e07b00f8b607403b03e9aa";  /* Encriptada */

open_html_tags("Modificar contraseña");
echo_username();

debug_msg($current.": recibido nombre referer: ".$_SESSION['referer']);
debug_msg($current.": propagando user id: ".$_SESSION['userid']);

if($_POST['newpass1'] && $_POST['newpass2']) {

   if($_POST['newpass1'] == $_POST['newpass2'] && 
      md5($_POST['newpass1']) != $default_passwd) {

      /* El id del usuario se ha propagado como variable de sesión */
      /* Recordar que el trigger llama a MD5 antes de cada UPDATE. */
      $query  = "UPDATE ".$table." SET passwd='".$_POST['newpass1']."'";
      $query .= "WHERE id=".$_SESSION['userid'];

      mysql_query($query, $link)
         or die("Error UPDATE password :".mysql_error());

      echo "Contraseña modificada.<br/>";

      /* Interfaz principal :      */
      /*  - Cambiar contraseña     */
      /*  - Cerrar sesión          */
      /*  - Altas, etc.            */
      include("interface.inc.php");

   } else {

      if(md5($_POST['newpass1']) == $default_passwd) {

         echo "Contraseña NO modificada<br/>";
         echo "Por motivos de seguridad, no está permitido ";
         echo "usar la password por defecto.<br/>";

      } else { 

         echo "¡Las contraseñas no coinciden!<br/>";
      }

      html_link_back("Volver a cambiar contraseña");
   }

} else {

   echo "El campo contraseña no puede estar vacío<br/>";
   html_link_back("Volver a cambiar contraseña");
}

close_html_tags();
?>
