<?
session_start();
include("/var/seguridad/db.inc.php");
include("func.inc.php");

$table  = "users";

open_html_tags("Enviar nueva contraseña");

$query  = "SELECT * FROM ".$table." WHERE email='".$_POST['email']."'"; 
$result = mysql_query($query, $link);

if(mysql_num_rows($result)) {   /* Si el email existe en la tabla */

   /* Sólo hay un resultado, con un fetch nos vale */
   $row = mysql_fetch_array($result);

   $passwd = generate_passwd(8);
   $msg    = "user: ".$row['username'].PHP_EOL;
   $msg   .= "new password: ".$passwd.PHP_EOL;

   if(mail($row['email'], "Nueva contraseña", $msg)) {

      /* Correo enviado, procedemos al UPDATE de la contraseña en la tabla */
      /* Recordar que el trigger llama a MD5 en cada UPDATE.               */
      $query = "UPDATE ".$table." SET passwd='".$passwd."'";
      $query .= "WHERE id=".$row['id'];

      mysql_query($query, $link)
         or die("Error UPDATE password :".mysql_error());

      echo "Compruebe su correo<br/>";
      echo "Nueva contraseña enviada a: ".$_POST['email']."<br/>";

   } else {
      echo "Error al enviar el mensaje.<br/>";
      echo "Revise la configuración de su servidor de correo.<br/>";
   } 

} else {
   echo "No existe ningún usuario registrado con esa dirección de correo<br/>";
}

html_link_back("Volver al formulario de login");
close_html_tags();

/* Liberar recursos y cerrar conexión con el servidor */
mysql_free_result($result);
mysql_close($link);

?>
