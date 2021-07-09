<?php

function format($str)
{
   return mb_convert_encoding($str, "UTF-8"); // Convert $output to UTF-8 encoding.
}

require_once "./SplFileObjectExtended.php";

$eng_closequote = '”';
$deu_closequote = '“';
/*
 * Look for sentence ender of punctutation--[.?!]--optionally followed by a end quote mark--that is, either an English or
 * a German end quote mark--follow by a space. But not if the space occurs at the end of the line: negative look ahead.
 *
 */
$regex = "/([.!?][{$eng_closequote}{$deu_closequote}]? )(?!\s*$)/";

$ifile = new SplFileObjectExtended("./results.txt" , "r");
$ofile = new SplFileObjectExtended("./sentence-results.txt" , "w");

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
