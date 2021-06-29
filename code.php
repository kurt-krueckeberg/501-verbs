<?php
  
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
    if (strlen($line) === 0) {
          $page[] = '';
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
    
    $new_order[] = $parts[0];
    $new_order[] = $parts[3];
    $new_order[] = $parts[1];
    $new_order[] = $parts[2];    
    return implode(',', $new_order);
}
         
function get_PrincipleParts($lines, $index)
{
  $regex_start = '/^Principle Parts:\s*(.*)$/';
  
  $pp = '';
          
  for ($i = $index; $i < count($lines); ++$i) {
        
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
    
    if (1 === preg_match('/^([a-zßöäü]+)((?:\s+[a-zößäü]+)*)\s*$/', $line, $matches)) {

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
    $regex_end = '/^END$/';
    $exampes = '';
        
    for ($i = $index; $i < count($lines); ++$i) {
        
        if (1 === preg_match($regex_start, $lines[$i])) {
            
            $examples_ = '';

            for(++$i; $i < count($lines); ++$i) {

                // Get all the example sentences.
               if (0 === preg_match($regex_end, $lines[$i], $matches)) {
              
                    $examples_ .= $lines[$i];
                    
                } else {  // It did match the delimeter of the example sentences, so we have all the example sentences.
            
                    $examples = str_replace("  ", " ", $examples_); // remove double spaces

                    return $examples;
                }
            }
        }
    }
    
   throw new NoType2ExamplesException("No EXAMPLE (s) found in lines starting with " . $lines[$index]); 
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
      
      if (0 === strpos($infinitive, "zwingen")) {
          $stop = 10;
      }
      
      // TODO: get page number, too?
      
      $principle_parts = get_PrincipleParts($page, 1);
      
      list($rc, $examples) = get_Examples_type1($page, 1);
      
      if ($rc === false) {
          
          $page = get_page($ifile);
          $examples = get_Examples_type2($page, 0);
      }
      
      $output = $infinitive . ' | PP: ' . $principle_parts . ' | ' . $examples . "\n";

       fputs($ofile, $output);
        
   } catch (Exception $e) {
       echo "Exception for Infinitive: " . $infinitive . "\n";    
       echo $e->getMessage() . "\n";
       exit();
   }
  
}
