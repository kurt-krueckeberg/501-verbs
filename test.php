<?php
/*
 * Determine is the string is the End Of a Sentence. A sentence in English and German may end with a punction mark of period, question mark of exclamation 
 * mark. They may optionally be preceded by an end quote mark; however, in output-pdf.txt, the German end quotation differs from that of the English.
 * 
 */
function is_EOS($str)
{
   $german_endquote = '“';
   $endlis_endquote = '”';
   $sentence_enders = "[\.\?!]";

   $regex = '/'. $sentence_enders . "(?:" . $german_endquote . '|' . $english_endquote . '?$/';

   $samples[] = ',,Wir würden vielleicht fleißiger arbeiten,wenn uns die Arbeit mehr interessierte“,';
   $samples[] = 'behaupteten die Arbeiter.'; 










   if (preg_match($regex, 
    
    
}
