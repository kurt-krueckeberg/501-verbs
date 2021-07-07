<?php
declare(strict_types = 1);

require_once "./verbs.php";
/*
 * Parameters: 
 *   $a - The sort array.
 *   $first - First index of the array to be searched (inclusive).
 *   $last - Last index of the array to be searched (exclusive).
 *   $key - The key to be searched for.
 *   $compare - A user defined function for comparison. Same definition as the one in usort
 *
 * Return:
 *   index of the search key if found, otherwise return (-insert_index - 1). 
 *   insert_index is the index of smallest element that is greater than $key or sizeof($a) if $key
 *   is larger than all elements in the array.
 */

function binary_search_(array $a, int $first, int $last, mixed $key, object $comparator)
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

function binary_search(array $a, mixed $key, object $comparator)
{
  return binary_search_($a, 0, count($a), $key, $comparator); 
}

class GermanComparator {

   private $collator;

   public function __construct()
   {
       $this->collator = new Collator('de_DE');
   }

   public function __invoke(string $str1, string $str2)
   {
       return $this->collator.compare($str1, $str2); 
   }
   
   public function sort(array &$array)
   {
       return $this->collator->sort($array);
   }
}

 
$germanComp = new GermanComparator();

$keys = array_keys($verbs);

shuffle($keys);

$germanComp->sort($keys);

print_r($keys);