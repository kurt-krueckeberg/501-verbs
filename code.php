<?php
 /*
  * Figure out where EXAMPLES, including SEPARABLE and INSEPARABLE
  *
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
    
    $page[] = $line . "\n";
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

class NoInfinitiveException extends Exception {
    
  public function __construct($msg, $code = 0, Throwable $previous = null) 
  {
      parent::__construct($msg, $code, $previous);
  }  
  /*
  public function getMessage()
  {
      return parent::getMessage();
  }
   * 
   */
}

class NoPrinciplePartsException extends Exception {
    
  public function __construct($msg, $code = 0, Throwable $previous = null) 
  {
      parent::__construct($msg, $code, $previous);
  }  
    
  public function errorMessage() {
    //error message
     return "No Principle Parts found on page. Line Number when exception occurred: " . $this->getLine();
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
        
        $pos = strpos($infinitive, "\n");
        
        if (false !== $pos) {
            
            $infinitive = substr($infinitive, 0, $pos);
        }
                 
    } else // This is an exception
        throw new NoInfinitiveException("No infinitive was found on the first line of the page, which is: " . $line);
    
    return $infinitive;
}

function get_Examples_type1(array $lines, $index)
{
    $regex_start = '/^Examples:(.*)$/';
    $regex_end = '/^7_9393_GermanVerbs_|^Principle Parts:/';
    $examples = '';
        
    for ($i = $index; $i < count($lines); ++$i) {
        
        if (1 === preg_match($regex_start, $lines[$i], $matches)) {
            
            $examples = $matches[1];

            for(; $i < count($lines); ++$i) {

                // Get all the example sentences.
               if (0 === preg_match($regex_end, $lines[$i], $matches)) {
              
                    $examples .= $lines[$i];
                    
                } else {  // It did match the delimeter of the example sentences, so we have all the example sentences.
            
                    $examples = preg_replace('/\s\s+/', ' ', $examples); // remove double spaces

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
            
            $examples_ = '';

            for(++$i; $i < count($lines); ++$i) {

                // Get all the example sentences.
               if (0 === preg_match($regex_end, $lines[$i], $matches)) {
                   
                    // Don't copy the '\n' at the end of each $lines[$i]        .
                    $examples_ .= substr($lines[$i], 0, -1);
                    
                } else {  // It did match the delimeter of the example sentences, so we have all the example sentences.
                    
                    $examples_ = preg_replace('/\n/', ' ', $examples_);  // replace '\n' with a space.
                    $examples = preg_replace('/\s\s+/', ' ', $examples_); // remove double spaces
                    
                    return array($examples, $i);
                }
            }
        }
    }
    
   throw new NoType2ExamplesException("No EXAMPLE (s) found in lines starting with " . $lines[$index]); 
}

function get_prefixVerbs(array $page, $index)
{
 $prefix_verbs = [];
 
 $pos = strpos('SEPARABLE', $page[$index]);
   
  if (0 === strpos('SEPARABLE', $page[$index])) {

      // Read lines until '/^#$/' encountered.
      list($index, $results) = parsePrefixVerb($page, $index + 1, '/^#$/');
      
      $prefix_verbs['sep'] = $results; 
  }      
  if (0 === strpos('INSEPARABLE', $page[$index])) {        

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

 for ($i = $index; 0 === preg_match($regex_end, $page[$i]); ++$i)  { // Loop until terminating line found
     
     // Is it a new definition?
     if (1 === preg_match($regex_verbDefn, $page[$i], $matches)) {
       
       if ($verb !== '') { // If this is not first verb encountered, add the prior verb results array.

           $results[$verb] = array($defn, $examples);
       } 

       // Gather up verb, defn, example sentences of prior verb  
       $verb = $matches[1];
       $defn = $matches[2];  

     } else // These are sample sentences
        
       $examples .= $page[$i];
  }

  // Add last verb results 
  $results[$verb] = array($defn, $examples);

  return $results;
}

$ifile = fopen("./new-output.txt", "r");
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
                $prefixVerbs = get_prefixVerbs($page, $index + 1);  
      }
      
      $output = $infinitive . ' | PP: ' . $principle_parts . ' | ' . $mainVerb_examples . "\n";

      if (count($prefix_verbs)) {

          foreach($prefixVerbs as $key => $value) {

             if (key[0] == 's')   
                 $output .= "SEPARABLE: | ";
             else 
                 $output .= "INSEPARABLE: | ";
                          
             $output .= $value[0] . " | " . $value[1]; 
          }
      }
 
      fputs($ofile, $output);
        
   } catch (Exception $e) {
       echo "Exception for Infinitive: " . $infinitive . "\n";    
       echo $e->getMessage() . "\n";
       exit();
   }
  
}
