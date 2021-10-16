<?php
use App\File\FileObject;

require_once './boot-strap/boot-strap.php';

boot_strap();

function format($str)
{
   return mb_convert_encoding($str, "UTF-8"); // Convert $output to UTF-8 encoding.
}

$eng_closequote = '”';
$deu_closequote = '“';
/*
 * Look for the end of the punctutation--the regex of [.?!]--optionally followed by a end quote mark. The quote mark can be either an English or
 * a German end quote mark followed by a space--but not if the space occurs at the end of the line: negative look ahead. 
 * 
 * What does the prior sentence mean?
 *
 */
$regex = "/([.!?][{$eng_closequote}{$deu_closequote}]? )(?!\s*$)/";

$ifile = new FileObject("./results.txt" , "r");
$ofile = new FileObject("./sentence-results.txt" , "w");

foreach($ifile as $line)  {

   $arr = explode(' | ', $line);

   $infin = $arr[0];

   $is_prefix = false; 

   if ($infin[0] == 'S') {
 
      $is_prefix = true;
      $infin = substr($infin, 5);

   } elseif ($infin[0] == 'I') {

      $is_prefix = true;
      $infin = substr($infin, 7);
   }
   
   $ofile->fwrite(format($infin . "\n"));

   $sentences = $arr[$is_prefix ? 2 : 3]; // Example sentences

   $sentences = explode(' @', $sentences);

   foreach($sentences as $sent) {
       
      $ofile->fwrite(format("$sent\n"));
   }

   $ofile->fwrite("\n");
}
