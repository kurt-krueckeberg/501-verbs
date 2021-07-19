<?php
use App\File\SplFileObjectExtended;

require_once './boot-strap/boot-strap.php';

boot_strap();

$eng_closequote = '”';
$deu_closequote = '“';
/*
 * Look for sentence ender of punctutation--[.?!]--optionally followed by a end quote mark--that is, either an English or
 * a German end quote mark--follow by a space. But not if the space occurs at the end of the line: negative look ahead.
 *
 */
$regex = "/([.!?][{$eng_closequote}{$deu_closequote}]? )(?!\s*$)/";

$ifile = new SplFileObjectExtended("./results.txt" , "r");
$ofile = new SplFileObjectExtended("./new-results.txt" , "w");

foreach($ifile as $line)  {

   $line = preg_replace($regex, '${1}@', $line);
  
   $output = mb_convert_encoding($line, "UTF-8"); // Convert $output to UTF-8 encoding.

   $ofile->fwrite($output . "\n");
}
