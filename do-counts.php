<?php
$ifile = fopen('./new-output.txt', 'r');
$ofile = fopen('./counts.txt', 'w');

$regex = '/^Examples:|^EXAMPLES/';

function get_line($ifile)
{
     $line = fgets($ifile);
     return trim($line);
}
$count_colon_examples = $count_other_examples = 0;
$line_cnt = 0;

while(!feof($ifile)) {
   
   $line = get_line($ifile);
   ++$line_cnt;
  
   if (1 == preg_match($regex, $line, $matches))  {
        if ($matches[0][1] == 'x')  ++$count_colon_examples;
        else  ++$count_other_examples;
   }
}

fputs($ofile, "Count of 'Example:' lines = " . $count_colon_examples . "\n");
fputs($ofile, "Count of 'EXAMPLE' lines = " . $count_other_examples . "\n");
