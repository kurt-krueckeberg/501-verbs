<?php
/*
 * Adds definition found $verbs[] (from dict.php) to the input file.
 *
 */
require_once "dict.php";
require_once "algorithms.php";
require_once "FileReader.php";

function format($str)
{
   return mb_convert_encoding($str, "UTF-8"); // Convert $output to UTF-8 encoding.
}

$ifile = new FileReader("./german-strong-irr-conjugations.txt"); 

$ofile = new SplFileObject("./new-german-conjugations.txt" , "w");

foreach($ifile as $line)  {

   $pos = strpos($line,'|'); // $pos + 1 is the start of the conjugation

   $verb = substr($line, 0, $pos); 

   $verb .= " ($dict[$verb])";  // Add definition in ().

   $output = $verb . substr($line, $pos) . "\n";

   $output = format($output);

   $ofile->fwrite($output);
}
