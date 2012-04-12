<?
session_start();

include("func.inc.php");
include("/var/seguridad/db.inc.php");

$table = "users";

open_html_tags("Modificar contraseña");

echo "En newpass.php: SESSION id: ".$_SESSION['userid']."<br/>";

if($_POST['newpass1'] && $_POST['newpass2']) {
   if($_POST['newpass1'] == $_POST['newpass2']) {

      /* El id del usuario se ha propagado como variable de sesión */
      $query  = "UPDATE ".$table." SET passwd=MD5('".$_POST['newpass1']."') ";
      $query .= "WHERE id=".$_SESSION['userid'];

      mysql_query($query, $link)
         or die("Error UPDATE password :".mysql_error());

      echo "Contraseña modificada.<br/>";

      /* Incrementar el campo del contador de logins. Si no lo hacemos,  */
      /* solicitaría cambiar la password de nuevo en el siguiente login. */
      $query  = "UPDATE ".$table." SET conns=conns+1 ";
      $query .= "WHERE id=".$_SESSION['userid'];

      mysql_query($query, $link)
         or die("Error UPDATE ".mysql_error());

      echo "Aquí habŕia que mostrar la interfaz principal<br/>";

   } else {
      echo "¡Las contraseñas no coinciden!<br/>";
      html_link_back("Volver");
   }

} else {
   echo "El campo contraseña no puede estar vacío<br/>";
   html_link_back("Volver");
}

close_html_tags();
?>
