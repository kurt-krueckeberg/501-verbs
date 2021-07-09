<?php
/*
 *
 * Reads sentence-results.txt and converts Germans sentences into cloze sentences with the English following the cloze sentnece in parenthesis.
 * Write results to new-sentence-results.txt 
 *
 */
require_once "dict.php";
require_once "algorithms.php";
require_once "./SplFileObjectExtended.php";

function format($str)
{
   return mb_convert_encoding($str, "UTF-8"); // Convert $output to UTF-8 encoding.
}

/*
 * Returns: associative array mapping verbs as key to its conjugation as a array: $array['some_verb'] = array(its conjugation)
 */
function get_conjugations($fname)
{
   $file = new SplFileOjectExtended($fname, "r");

   $conjugations = [];
 
   foreach ($file as $line) {
      $pos = strpos($line, '|');

      $verb = substr($line, 0, $pos);

      $conj = substr($line, $pos + 1);

      $conj_arr = explode(',', $conj);
      $conjugations[$verb] = $conj_arr; 
   }
   return $conjuations;
}

function get_verbBlock($file)
{
  $infinitive = $file->fgets();
  if ($infinitive == '') return false;
 
  $sents = [];

  while (($line = $file->fgets()) != '') {
     
     $sents[] = $line;      
  }

  return array($infinitive, $sents);
}

function transform(string $verb, array $sents, array $conjs)
{
 $result = [];
  for ($sents as $sent) {

   $pos = strpos($sent, '@');
   $deu_sent = substr($sent, 0, $pos);
   $eng_sent = substr($sent, $pos + 1);

   $result[] = cloze($deut_sent, $eng_sent, $conjs);    
     
  }
  return $result;
}

function cloze(string $verb, string $deu_sent, $eng_sent, array $conjs)
{
   str???
}

function write_out(string $verb, array $new_sents)
{

}

$ifile = new SplFileObjectExtended("./sentence-results.txt" , "r");
$ofile = new SplFileObjectExtended("./new-sentence-results.txt" , "w");

 $conjs = get_ conjugations("./german-strong-irr-conjugations.txt");

 $block = get_verbBlock($ifile);

 do {

   list($verb, $sents) = $block;

   $new_sents = transform($verb, $sents, $conjs);
   write_out($verb, $new_sents);

   $block = get_verbBlock($ifile);

 }  while($block != '')
