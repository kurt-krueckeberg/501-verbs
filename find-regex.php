<?php

use App\File\SplFileObjectExtended;

require_once "./boot-strap/boot-strap.php";

error_reporting(E_ALL ^ E_WARNING);  

boot_strap();
 
if ($argc != 3) {
   echo "Enter the name of the file to check followed by the regex.\n";
   return;
}

$file = new SplFileObjectExtended($argv[1], "r");
$regex = mb_convert_encoding($argv[2], 'UTF-8'); 

$regex_len = strlen($regex); 

foreach ($file as $data) {

  $line = trim($data);

  if ($line === false)
      break;

   $line_no = $file->get_lineno();

   $results = preg_match('/(' . $regex . ')/u', $line, $matches, PREG_OFFSET_CAPTURE);
   
   if ($results === 1) {
    
       echo "Line Num "  . $file->get_lineno() . ': ' . $line . "\n"; /* " $regex  found <--- Line = " .  $line . "\n"; */
   }
} 

// So try reading the file 1235...html
// and preg_match() searching for Überblick.

