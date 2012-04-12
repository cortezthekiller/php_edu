<?

/* Esta función genera código de apertura xhtml 1.0 validado */
function open_html_tags($title)
{
   echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' ";
   echo "'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
   echo "<html xmlns='http://www.w3.org/1999/xhtml'>";
   echo "<head>";
   echo "<title>".$title."</title>";
   echo "<meta http-equiv='Content-type' content='text/html;charset=UTF-8' />";
   echo "</head>";
   echo "<body>";
}

function close_html_tags()
{
   echo "</body>";
   echo "</html>";
}

/* Esta función comprueba si se ha pasado el nombre del formulario llamante */
/* mediante una variable de sesión y en ese caso muestra un link al mismo.  */
function html_link_back($msg)
{
   if(!empty($_SESSION['form'])) {
      echo "<a href='http://".$_SESSION['form']."'>".$msg."</a>";
      unset($_SESSION['form']);   /* Liberar variable(s) de sesión */
   } else {
      echo "Necesita habilitar las cookies en su navegador</br>";
      exit(-1);
   }
}

/* Esta función genera una contraseña con caracteres aleatorios          */
/* En el parámetro $length se le pasa la longitud de caracteres deseada. */
/* Devuelve un string con la password generada.                          */
function generate_passwd($length)
{
   /* Caracteres utilizados en la aleatorización */
   $ch  = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*"; 

   $passwd = str_shuffle($ch);

   return substr($passwd, 0, $length);
}

?>
