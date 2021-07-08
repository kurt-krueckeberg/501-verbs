<?php
require_once "SplFileObjectExtended.php";

require_once "./algorithms.php";

require_once "./dict.php";

$ifile = new SplFileObjectExtended("./results.txt", "r");
$rfile = new SplFileObjectExtended("./new-results.txt", "w");

$keys = array_keys($verb); // <-- Need 

foreach($ifile as $lineno => $line) {

   if (0 === (strpos($line, "SEP")) || 0 === (strpos($line, "INS"))) {

               $rfile->fwrite($line . "\n");
   } else { 

       $parts = explode(" | ", $line);
       
       $output = '';

       foreach ($parts as $key => $value) {

            if ($key == 1) {
               $output .= ' | ' .  $verbs[$parts[0]];
            }
   
            $output = $value . ' | ' . $value;
        }

        $output = substr($output, 0, -3);
   
        $rfile->fwrite($output . "\n");
  }
}
