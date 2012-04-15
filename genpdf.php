<?
include_once("/var/seguridad/db.inc.php");
include("fpdf.php");
define('FPDF_FONTPATH', $_SERVER['DOCUMENT_ROOT'].'/cursophp/fontspdf/');

$table = "alumnos";   /* Tabla con los datos de los alumnos */

/* Array con los nombres de las tablas de materias */
$tables = array("matematicas", "historia", "tecnologia");
$cont   = 0; /* Contador número de alumnos en el curso */

/* Limpiar buffer de salida por si se hizo un Output de un pdf anterior */
/* (a veces me daba error si no limpiaba el buffer).                    */
ob_end_clean();

$pdf = new FPDF('P', 'mm', 'A4'); /* Crear PDF formato A4 vertical */

$pdf->SetDisplayMode('fullpage'); /* Visualizar página completa en pantalla */

/* Margen izqdo=15mm, dcho=15mm, superior=25mm.                       */
$pdf->SetMargins(15, 25, 15); 

$pdf->AliasNbPages(); /* Establecemos el alias del totalizador de páginas */

/* Desactivamos el salto de página automático.             */
/* La inserción de nuevas páginas se realizará manualmente */
$pdf->SetAutoPageBreak(0); 

$cuentalineas  = 1;   /* Contador de líneas insertadas por cada página   */
$lineas_pagina = 35;  /* Máximo número de líneas insertar en cada página */
$interlinea    = 4.5; /* Separación en mm entre líneas                   */ 

if ($cuentalineas==1) { 
   /* Primera línea de una página (encabezado) */
   /* Establecemos los colores y tipografía    */
   $pdf->SetDrawColor(255, 255, 255); 
   $pdf->SetFillColor(100); 
   $pdf->SetTextColor(255, 255, 255); 
   $pdf->SetLineWidth(0.3); 
   $pdf->SetFont("Arial", "", 8.5); 

   /* Insertamos una nueva página (estamos en la primera línea) */
   $pdf->Addpage(); 

   /* Escribimos las cabeceras de las tablas */
   $pdf->Cell(10, 6, "nº", 1, 0, C, 1); 
   $pdf->Cell(60, 6, "Nombre y apellidos", 1, 0, C, 1); 
   $pdf->Cell(20, 6, "Matemáticas", 1, 0, C, 1); 
   $pdf->Cell(20, 6, "Historia", 1, 0, C, 1); 
   /* Ultima celda del encabezado (por eso 1 antes de C) */
   $pdf->Cell(20, 6, "Tecnología", 1, 1, C, 1); 
} 

/* Consultar tabla de alumnos del curso, obtenemos cada registro en $fila.   */
/* Podría haber hecho una consulta única a múltiples tablas, como en el      */
/* script anterior, sería más efectivo. Pero el código para armar la query   */
/* es más lioso, porque al llamarse el campo 'nota' igual en varias tablas   */ 
/* no puedo utilizar arrays asociativos. Otra opción hubiera sido cambiar el */
/* nombre del campo en las tablas: nota_mates, nota_historia, etc.           */

$query  = "SELECT * FROM ".$table." WHERE curso='".$_GET['curso']."'";
$query .= " ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";

$result = mysql_query($query, $link)
             or die("Error SELECT: ".mysql_error());

while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {

   /* Asignar colores y tipografias distintas a filas pares e impares */
   if($cuentalineas%2 == 0) { 
      $pdf->SetDrawColor(255, 255, 255); 
      $pdf->SetFillColor(200); 
      $pdf->SetTextColor(0, 0, 0); 
      $pdf->SetLineWidth(0.3); 
      $pdf->SetFont("Arial", "", 9); 
   } else { 
      $pdf->SetDrawColor(255, 255, 255); 
      $pdf->SetFillColor(255); 
      $pdf->SetTextColor(0, 0, 0); 
      $pdf->SetLineWidth(0.3); 
      $pdf->SetFont("Arial", "", 9); 
   } 

   $cont++;   /* Incrementamos contador de alumnos en el curso */
   $pdf->Cell(10, 6, $cont, 1, 0, L, 1); 

   /* Componemos el nombre y apellidos de cada alumno */
   $alumno = $fila['apellido1']." ".$fila['apellido2'].", ".$fila['nombre'];
   $pdf->Cell(60, 6, $alumno, 1, 0, L, 1); 

  /* Para cada alumno (clave dni) consultamos las tablas */
  /* de materias y obtenemos la nota en cada materia.    */
  foreach($tables as $materia) {
     $query  = "SELECT nota FROM ".$materia;
     $query .= " WHERE (DNI='".$fila['DNI']."')";
     $result_materia = mysql_query($query, $link)
                          or die("Error SELECT: ".mysql_error());

     /* No hacemos fetch, utilizamos directamente mysql_result() */
     $nota = mysql_result($result_materia, 0, 'nota');

     /* Comprobar si estamos en la última celda de la fila */
     $last_cell = 0;
     if($materia == 'tecnologia') {
        $last_cell = 1;
     }

     $pdf->Cell(20, 6, $nota, 1, $last_cell, C, 1);

     mysql_free_result($result_materia); /* Liberar recursos */
  }

  $cuentalineas++; /* Incrementamos el contador de líneas */

  /* Si llegamos al final de página ponemos el contador de líneas a 1 */
  if ($cuentalineas == $lineas_pagina) {
     $cuentalineas = 1; 
  }

}   /* while - todos los alumnos en tabla2 */

$pdf->Output(); /* Visualizar el documento */

mysql_free_result($result); /* Liberar recursos       */
mysql_close($link);         /* Liberar conexión mysql */
?>
