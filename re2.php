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
/*
 TODO: Return one, two or three blocks, depending on whether there are paried prefix verbs in the block. 
 */
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
    
     /*
       Handle SEPARABLE and INSEPARABLE lines. If regex matches, then:
       $1 == SEPARABLE or INSEPARABLE
       $2 == verb
       $3 == definition 
 
      1. Insert 'verb%its definition' into verbs.txt immediately after the verb it is paired with--the non-prefix verb--in the book.
      2. Terminate the existing block and create a new block with the separable or inseparable verb in this form:

         verb ||Example sentences 
      */
     if (1 === preg_match('/^(?:SEPARABLE|INSEPARABLE)\s|\s([a-zöäü]+)-([a-zöäü]+)\s?\|/', $line, $matches)) {
        // Extract definition of SEP/INSEP verb and remove 
        
     }

   } while(!empty($line));

   $output = implode(' ', $arr);

   return $output;
}   

function get_verb($str)
{
    $pos = strpos($str, " |");;
    return substr($str, 0, $pos);
}

$ifile = fopen("./results.txt", "r");
$sfile = fopen("./results-sep-insep-verbs.txt" , "w");
$ofile = fopen("./results-non-sepinsp.txt" , "w");

/*
 TODO: The essential verbs can contain Prefix Verb forms, SEPARABLE and INSEPARABLE.
 These are related but separate forms of the essentiall verbs. We want their to:
 1. To get thei definitions, which follow a dash (--) and
 2. Their example sentences

We will add the definitions to verbs.txt and example sentences we will add to results, perhaps along with Principle Parts, which
will be the same as the essential verb.
 */

while(!feof($ifile)) {
  
   $verb_line = get_block($ifile);

   $verb = get_verb($verb_line);
 
   echo $verb . "\n";
   continue;
 
   if (1 === preg_match('/^SEPARABLE|^INSEPARABLE/', $verb)) {

      $verb_line = preg_replace('/(?:^SEPARABLE|^INSEPARABLE)\s\|\s/', '', $verb_line) . "\n";

      fputs($sfile, $verb_line . "\n");   
   } else {
       
      fputs($ofile, $verb_line . "\n");   
   }

}
