<?php

namespace App\Utilities;

use Barryvdh\DomPDF\Facade\Pdf as PDF;



class PdfGeneratorUtil
{
   /*
     *Funcion para generar un pdf y mostrarlo en el navegador
     * 
     * @param string $view
     * @param array $data
     *    
    */
   public static function ShowPdf(string $view, array $data, string $filename = "PDF")
   {
      ini_set('memory_limit', '512M');
      set_time_limit(120);

      $pdf = PDF::loadView($view, $data)->setPaper("a4", "portrait");

      //Validamos el nomber del archivo para que no contenga error o espacios en blanco
      $filename = str_replace(" ", "_", $filename);

      //Retornamos una vista/preview del pdf
      return $pdf->stream($filename . "_" . date("Y-m-d") . ".pdf");
   }
}
