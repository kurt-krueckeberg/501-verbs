<?php
 
function get_line($ifile)
{
   if (!feof($ifile)) {

     $line = fgets($ifile);

     return trim($line);

  } 
  
  return '';
}
/*
 * Returns an array of strings corresponding to the lines of the page. Each is terminated by '\n'.
 */
function get_page($ifile)
{
  $page = array();  
  
  while(!feof($ifile)) {
      
    $line = get_line($ifile);

    if ($line == '') {
          $page[] = '';
          return $page; 
    }
      
    if (0 === strpos($line, "PAGE_S")) break;
  }
    
  while(!feof($ifile)) {
      
    $line = get_line($ifile);
    
    if (0 === strpos($line, "PAGE_E")) break;
    
    $page[] = $line; 
  }
    
  return $page;
}

function advance_to($regex, $ifile)
{
  while(!feof($ifile)) {

      $line = get_line($ifile);
       
      if (1 == preg_match($regex, $line)) 
              break;
 }
}

function reorder_princ_parts($pp)
{    
    $parts = explode(',', $pp);
    
    if (count($parts) < 4) 
        return $pp;
    
    $reorder[] = $parts[0];
    $reorder[] = $parts[3];
    $reorder[] = $parts[1];
    $reorder[] = $parts[2];    

    return implode(',', $reorder);
}
         
function get_PrincipleParts($lines, $start_index)
{
  $regex_start = '/^Principle Parts:\s*(.*)$/';
  
  $pp = '';
          
  for ($i = $start_index; $i < count($lines); ++$i) {
        
        if (1 === preg_match($regex_start, $lines[$i], $matches)) 
            $pp = $matches[1];
  }
  
  $pp = preg_replace('/\s\s+/', ' ', $pp);
        
  $pp = preg_replace("/\sto\s.*$/", '', $pp);
   
  $pp = reorder_princ_parts($pp);
  
  return $pp;
}
class NoType2ExamplesException extends Exception {
    
  public function __construct($msg, $code = 0, Throwable $previous = null) 
  {
      parent::__construct($msg, $code, $previous);
  }  
};
 
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

/* Add extra blank at end if line does not have it. 
 * If it causes double spaces (because we trimmed-and-padded a sentence fragment), this is handled below. But this way, we don't have to
 * test when sentence fragments might or might not properly end in a space.  
 */

function adjust_line($str)
{
  $str .= (substr($str, -1) != ' ') ? ' ' : '';
  return $str;
}

function get_Examples_type1(array $lines, $index)
{
    $regex_start = '/^Examples:$/';
    $regex_end = '/^7_9393_GermanVerbs_|^Principle Parts:/';
    $examples = '';
        
    for ($i = $index; $i < count($lines); ++$i) {
        
        if (1 === preg_match($regex_start, $lines[$i], $matches)) {

            for(++$i; $i < count($lines); ++$i) {

                // Get all the example sentences.
               if (0 === preg_match($regex_end, $lines[$i], $matches)) {
            
                    //$examples .= $lines[$i];
                    $examples .= adjust_line($lines[$i]);
 
                } else {  // It did match the delimeter of the example sentences, so we have all the example sentences.
            
                    $examples = preg_replace('/\s\s+/', ' ', $examples); // remove double spaces
                    $examples = preg_replace('/,,/', '„', $examples); // remove non-german introductory quote with correct introd. quote.

                    return array(true, $examples);
                }
            }
        }
    }
    
    return array(false, ''); 
}

function get_Examples_type2(array $lines, $index)
{
    $regex_start = '/^EXAMPLES\s*$/';
    $regex_end = '/^Prefix Verbs|^7_9393_/';
    $exampes = '';
        
    for ($i = $index; $i < count($lines); ++$i) {
        
        if (1 === preg_match($regex_start, $lines[$i])) {
            
            $examples = '';

            for(++$i; $i < count($lines); ++$i) {

                // Get all the example sentences.
               if (0 === preg_match($regex_end, $lines[$i], $matches)) {
                   
                   $examples .= adjust_line($lines[$i]);
                                       
                } else {  // It did match the delimeter of the example sentences, so we have all the example sentences.
                    
                    $examples = preg_replace('/\n/', ' ', $examples);  // replace '\n' with a space. 
                    $examples = preg_replace('/\s\s+/', ' ', $examples); // remove double spaces
                    $examples = preg_replace('/,,/', '„', $examples); // remove non-german introductory quote with correct introd. quote.

                    return array($examples, $i);
                }
            }
        }
    }
    
   throw new ErrorException("No EXAMPLE (s) found in lines starting with " . $lines[$index]); 
}

function get_prefixVerbs(array $page, $index)
{
 $prefix_verbs = [];
 
 $pos = strpos($page[$index], 'SEPARABLE');
   
  if (0 === strpos( $page[$index], 'SEPARABLE')) {

      // Read lines until '/^#$/' encountered.
      list($index, $results) = parsePrefixVerb($page, ++$index, '/^#$|^7_9393_/');
            
      $prefix_verbs['sep'] = $results; 
  }
  
  if (++$index >= count($page))  // There are no inseparable verbs.
      return $prefix_verbs;
  
  if (0 === strpos($page[$index], 'INSEPARABLE')) {        

      // Read lines until '/^7_9393_G/' encountered.
      list($index, $results) = parsePrefixVerb($page, $index + 1,  '/^7_9393_G/');
      
      $prefix_verbs['insep'] = $results; 
  } 

  return $prefix_verbs;
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

advance_to('/Page 32\s*$/', $ifile);

while(!feof($ifile)) {
  
   $page = get_page($ifile); // page is a string with '\n' separating each 'line' within it.
  
   if (count($page) == 1 && empty($page[0]))
        break;

   foreach ($page as $line) {


        if (strpos($enc, "UTF") === 0) {

                // This should always find words of at least three characters.  
                $regex_verb = '/(hätte)\s/'; // verb may come in several parts.
         
    		if (1 === preg_match($regex_verb, $line, $matches)) {

                     $m1 = $matches[1];

               	     $debug = 10;
                } 
               ++$debug;  
        } else {
            
              $debug = 11;
        }
     
   }
   $infinitive = '';
   $principle_parts = '';
   $examples = '';
   $prefix_verbs = [];
   
   try {
  
   } catch (Exception $e) {
       echo "Exception for Infinitive: " . $infinitive . "\n";    
       echo $e->getMessage() . "\n";
       exit();
   }
  
}
