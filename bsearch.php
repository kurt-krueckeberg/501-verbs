<?php
declare(strict_types = 1);

require_once "./verbs.php";
// callable is more flexible than 'object'. It 

// USE
function binary_search_(array $a, int $first, int $last, string $key, callable /* object type also works */ $comparator)
{
    $lo = $first; 
    $hi = $last - 1;

    while ($lo <= $hi) {

        $mid = (int)(($hi - $lo) / 2) + $lo;
   
        $cmp = $comparator($a[$mid], $key);

        if ($cmp < 0) {
            
            $lo = $mid + 1;

        } elseif ($cmp > 0) {

            $hi = $mid - 1;

        } else {

            return true; //$mid;
        }
    }
    return false;
}

function binary_search(array $a, string $key, callable $closure)
{
  return binary_search_($a, 0, count($a), $key, $closure); 
}
// USE
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

$keys = array_keys($verbs);

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
