<?php
/*
Reformats the output so the sample sentences are one string, and all the verb info is on one line.
 */
require_once "./essential-verbs.php";
require_once "./bsearch.php";
  
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

function get_verb($str)
{
    $pos = strpos($str, " |");;
    return substr($str, 0, $pos);
}


$ifile = fopen("./results.txt", "r");
$oNonfile = fopen("./results-nonessential-verbs.txt" , "w");
$oEssfile = fopen("./results-essential-verbs.txt" , "w");

while(!feof($ifile)) {
  
   $verb_line = get_block($ifile);
   
   if ($verb_line === false)
      return;
   
   $output = $verb_line . "\n";
   
   $verb = get_verb($verb_line);
   
   $b = binary_search($essential_verbs, $verb);
 
   if ($b === true)  {// Write it to the essential verb list
       fputs($oEssfile, $output);   
   } else 
       fputs($oNonfile, $output);   
   
}
