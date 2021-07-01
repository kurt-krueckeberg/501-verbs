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
    
    $regex_verb = '/^((?:\(sich\)\s+)?[a-zöäü]{3,})\s*)$/';    // verb line may optionally start with '(sich) '

    if (1 === preg_match($regex_verb, $line, $matches)) {

        for ($i = 1; $i < count($matches); ++$i) {
         
           if (0 != strlen($matches[$i]))
              $infinitive .= $matches[$i];
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
    $regex_end = '/^Prefix Verbs\s*$/';
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
  $regex_insep_end = '/^7_9393_/'; // The line that signals the end of inseparable verbs
  $regex_verbDefn      = '/^((?:\(sich\)\s+)?[a-zöäü]{3,})—(.*)$/';

  if (1 === preg_match('/^SEPARABLE\s$/', $page[$index])) {

        for ($i = $index + 1; $i < count($page) && false === strpos($page[$i], "INSEP") ; ++$i) {

            // Is it a verb + definition?  
            // TODO: Note: There wil frequently be more than one verb + definition, followed by example sentences.
            if (1 === preg_match($regex_verbDefn, $page[$i], $matches) {

            } else { // It is an examples sentence(s).
              
            }
  }
  if (0 === strpos(preg_match('/^INSEPARABLE\s$/', $page[$index])) {        

        for ($i = $index + 1; $i < count($page) && false === strpos($page[$i], "7_9393_") ; ++$i) {

            // TODO: Note: There wil frequently be more than one verb + definition, followed by example sentences.
            if (1 === preg_match($regex_verbDefn, $page[$i], $matches) {

            } else { { // It is an examples sentence(s).
              
            }
        }
  } 
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
   
   try {
      
      $infinitive = get_infinitive($page[0]);
      
      if (0 === strpos($infinitive, "arbeiten")) {
          $stop = 10;
      }
      
      $principle_parts = get_PrincipleParts($page, 1);
      
      list($rc, $mainVerb_examples) = get_Examples_type1($page, 1);
      
      if ($rc === false) {
          
          $page = get_page($ifile);
          list($mainVerb_examples, $index) = get_Examples_type2($page, 0);

          // TODO: Complete this method
          /*
           * Each Prefix verbs are either/or both Separable or Inseparable. Each verb has a definition following the dash that follows the verb. Then, on the
             next line it has examples. The Separable verbs/examples alwasy begin before the Inseparable. So the Separable examples are terminated by
             the line '/^INSEPARABLE\s*$/'
             
             The Inseparable are terminated by: '/7_9393_/'
                
             Format for Sep/Insp verbs output in results.txt:
             SEP:verb1%definition%examples. 
             SEP:verb2%definition%examples. 
             ....
             INSEP:verb3%definition%examples
             INSEP:verb4%definition%examples
           * 
           */ 
          $prefixVerbs = get_prefixVerbs($page, $index);  

  
          // TODO: get_prefixVerbs(start a $page[$prefix_row]
      }
      
      $output = $infinitive . ' | PP: ' . $principle_parts . ' | ' . $mainVerb_examples . "\n";

      fputs($ofile, $output);
        
   } catch (Exception $e) {
       echo "Exception for Infinitive: " . $infinitive . "\n";    
       echo $e->getMessage() . "\n";
       exit();
   }
  
}
