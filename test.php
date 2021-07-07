<?php
 
function get_infinitive($line)
{
    $infinitive = ''; 
    $regex_verb = '/^((?:[a-zöäüß]{3,}\s?)+)\s*$/'; // verb may come in several parts.
         
    if (1 === preg_match($regex_verb, $line, $matches)) {

        for ($i = 1; $i < count($matches); ++$i) {
         
           if (0 != strlen($matches[$i]))
              $infinitive .= $matches[$i];
        }
                
    } else // This is an exception
        throw new ErrorException("No infinitive was found on the first line of the page, which is: " . $line);
    
    return $infinitive;
}


/*
 * Input: $page[$index] is the first element of $page with which to begin
 * Returns:
 * An associative array of the form:
 *    $a['sep']   => { 0 => the definition of the verb, 1 => A string of examples sentences. }
 *    #a['insep'] => { 0 => the definition of the verb, 1 => A string of examples sentences. }
 */

function parsePrefixVerb($page, $index, $regex_end)
{
 // Note: The type of dash used in the regex--and there seems to be more than one type--must be correct, or not match will occur!
 $regex_verbDefn = '/^((?:\(sich\)\s+)?[a-zöäüß]{3,})—(.*)$/';
                                     
 $examples = $verb = $defn = '';

 $results = [];

 for (; 0 === preg_match($regex_end, $page[$index]); ++$index)  { // Loop until terminating line found
     
    if (1 === preg_match($regex_verbDefn, $page[$index], $matches)) {  // Is this line a verb-definition?
       
        if ($verb !== '') { // If this is not first verb encountered, insert the prior verb results into the array.
                      
           $examples = preg_replace('/\s\s+/', ' ', $examples);
           
           $results[$verb] = array($defn, $examples);
           $examples = ''; // So that the new verb doesn't have sample sentences from the prior verb, we reset it to empty. 
        } 

        // Gather up verb, defn, example sentences of prior verb  
        $verb = $matches[1];
        $defn = $matches[2];  

    } else {// These are sample sentences
        
       $examples .= adjust_line($page[$index]);
       
       $examples = preg_replace('/““/', '" “', $examples);
       $examples = preg_replace('/,,/', '„', $examples); // Replace incorrect quote mark.
   }
 }
 // Add last verb results 
 $results[$verb] = array($defn, $examples);

  return array($index, $results);
}

$ifile = fopen("./output-pdf.txt", "r");
$ofile = fopen("./results.txt", "w");

advance_to('/Page 32\s*$/', $ifile);

while(!feof($ifile)) {
  
   $page = get_page($ifile); // page is a string with '\n' separating each 'line' within it.

   if (count($page) == 1 && empty($page[0]))
        break;

   $infinitive = '';
   $principle_parts = '';
   $examples = '';
   $prefix_verbs = [];
   
   try {
     
      $infinitive = get_infinitive($page[0]);
       
      $principle_parts = get_PrincipleParts($page, 1);
      
      list($rc, $mainVerb_examples) = get_Examples_type1($page, 1);

      if ($rc === false) {
          
          $page = get_page($ifile);
          list($mainVerb_examples, $index) = get_Examples_type2($page, 0);
          
          /* 
            get_prefixVerbs returns an associative array of the form:

	 	$a['sep']  => { 0 => the definition of the verb, 1 => A string of examples sentences. }
	    	a['insep'] => { 0 => the definition of the verb, 1 => A string of examples sentences. }
           */
          if (($index + 1)< count($page))
                $prefix_verbs = get_prefixVerbs($page, $index + 1);  
      }
      
      $output = $infinitive . ' | ' . $principle_parts . ' | ' . $mainVerb_examples . "\n";

      if (count($prefix_verbs)) {

          foreach($prefix_verbs as $prefix_type => $verbs) {
       
             $prefix_4output = ($prefix_type[0] == 's') ? "SEP:" : "INSEP:";
             
             foreach($verbs as $verb => $array) {
                          
                $output .= $prefix_4output . " | ". $verb . " | ". $array[0] . " | " . $array[1] . "\n"; 
             }
          }
      }
      
      $output = mb_convert_encoding($output, "UTF-8"); // Convert $output to UTF-8 encoding.
      
      fputs($ofile, $output);
        
   } catch (Exception $e) {
       echo "Exception for Infinitive: " . $infinitive . "\n";    
       echo $e->getMessage() . "\n";
       exit();
   }
  
}
