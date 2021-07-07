<?php
require_once './algorithms.php';
require_once "./dict.php";


// Use
class GermanComparator {

   private $collator;

   public function __construct()
   {
       $this->collator = new Collator('de_DE');
   }

   public function __invoke(string $str1, string $str2)
   {
       return $this->collator->compare($str1, $str2); 
   }
    
   public function compare(string $str1, string $str2)
   {
       return $this->collator->compare($str1, $str2);
   }
  
   public function sort(array &$array)
   {
       return $this->collator->sort($array);
   }
}

try {
 
    $germanComp = new GermanComparator();
    
    $keys = array_keys($dict);
    
    shuffle($keys);
    
    $germanComp->sort($keys);
    $rc = $germanComp->compare($keys[20], $keys[22]);
    print_r($keys);
    
    echo "\n" . $rc . "\n";
    
    $closure = function (string $str1, string $str2) use ($germanComp) { return $germanComp->compare($str1, $str2); };
    
    binary_search($keys, 'ächzen', $closure); 
    
    binary_search($keys, 'ächzen', $germanComp);
    
} catch(Exception $e) {
    
    echo $e->getMessage();
}
