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

function is_essential(string $verb_line)
{
   $verb = get_verb($verb_line);
   
   return binary_search(EssentialVerbs::getVerbs(), $verb);
}

$ifile = fopen("./results.txt", "r");
$oNonfile = fopen("./results-nonessential-verbs.txt" , "w");
$oEssfile = fopen("./results-essential-verbs.txt" , "w");

/*
 TODO: The essential verbs can contain related Prefix Verbs. both SEPARABLE and INSEPARABLE.
 There can be more than one separable and inseparable prefix verbs.
 Note: There can be several
 We want their to:
 1. To get thei definitions, which follow a dash (--) and
 2. Their example sentences

We will add the definitions to verbs.txt and example sentences we will add to results, perhaps along with Principle Parts, which
will be the same as the essential verb.
 */

while(!feof($ifile)) {
  
   $verb_line = get_block($ifile);
   
   if ($verb_line === false)
      return;

   if (is_essential($verb_line))  // Write it to the essential verb list
       fputs($oEssfile, $verb_line . "\n");   
   else 
       fputs($oNonfile, $verb_line . "\n");   
}
