<?php
use \SplFileObject as File;
/*
 * Adds definition found $verbs[] (from dict.php) to the input file.
 *
 */
require_once "dict.php";
require_once "algorithms.php";
require_once "./SplFileObjectExtended.php";

function format($str)
{
   return mb_convert_encoding($str, "UTF-8"); // Convert $output to UTF-8 encoding.
}

$ifile = new File("./german-strong-irr-conjugations.txt" , "r");
$ifile->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

$ofile = new File("./new-german-conjugations.txt" , "w");
$ofile->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

foreach($ifile as $line)  {

   $pos = strpos($line,'|'); // $pos + 1 is the start of the conjugation

   $verb = substr($line, 0, $pos); 

   $verb .= " ($dict[$verb])";  // Add definition in ().

   $output = $verb . substr($line, $pos) . "\n";

   $output = format($output);

   $ofile->fwrite($output);
}
