<?php
/*
Reformats the output so the sample sentences are one string, and all the verb info is on one line.
 */
  
function get_line($ifile)
{
   if (!feof($ifile)) {

     $line = fgets($ifile);

     return trim($line);

  } 
  $nothing = '';
  return $nothing;
}

function get_block($ifile)
{
   $line = get_line($ifile);
   
   if (empty($line)) {
       if (feof($ifile))
           return false;
       else
           return '';
   }
  
   $arr = array();

   do {

     $arr[] = $line;   
     $line = get_line($ifile);

   } while(!empty($line));

   $output = implode(' ', $arr);

   $output = preg_replace('/\s\s+/', ' ', $output);
   
   return $output;
}   

$ifile = fopen("./results.txt", "r");
$ofile = fopen("./experimental-results.txt" , "w");

while(!feof($ifile)) {
  
   $verb_line = get_block($ifile);

   if ($verb_line === false)
      return;
   
   $output = $verb_line . "\n";

   fputs($ofile, $output);   
}
