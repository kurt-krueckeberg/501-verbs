<?php

$ifile = fopen('./output.txt', 'r');
$ofile =  fopen('./examples.txt', 'w');

$regex_start = "/^Examples:(.+)$/";
$regex_end = "/^7_9393_GermanVerbs_1|^Principle Parts:/";

while(!feof($ifile)) {

    $line = fgets($ifile);

    if (1 === preg_match($regex_start, $line, $matches)) {

         $example = $matches[1];
           
         // Read and append subsequent lines as long as they don't match $regex_end. If the do, continue outer loop.
         while (1) {
               $line = fgets($ifile); 
               
               $rc = preg_match($regex_end, $line, $matches); 
               
               if ($rc === 1)   
                   break;
               
               $example .= ' ' . $line;
         } 
         
         $output = "START_EXAMPLE:\n" . $example . "\nEND_EXAMPLE\n";
         fputs($ofile, $output);
   }
}

