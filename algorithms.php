<?php
declare(strict_types = 1);

/*
 * $comparator can be either a lambda or a binary predicate object (like GermanComparator below).
 * Note: Type 'object' can be used in place of 'callable' without code breaking.
 *
 * Examples:
 *
 * 1. Find 'bekommen' in array of german verbs using the GermanCompartor predicate object (defined below). 
 *
 *    binary_search($german_verbs, "bekommen", new GermanComparator('de_DE'));
 *   
 * 2. Use a lambda function as the comparator
 *
 *  $germanComp = new Collator('de_DE');
 *
 *  $closure = function (string $str1, string $str2) use ($germanComp) { return $germanComp->compare($str1, $str2); };
 *  
 *  binary_search($keys, 'ächzen', $closure); 
 *
 */
function binary_search_(array $a, int $first, int $last, string $key, callable $comparator)
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

function binary_search(array $a, string $key, callable $comparator)
{
  return binary_search_($a, 0, count($a), $key, $comparator); 
}

/*
 * This class is both a Collator for the German 'de_DE' locale and a predicate object
 * that overloads the binary function call operator (using PHP's __invoke magic method).
 *
 * It can be passed to binary_search as the comparator parameter.
 *
 *   // PHP lambda/closure method
 *   $germanComp = new GermanComparator; 
 *
 *   Note: You can also simply do:
 *   $closure = function (string $str1, string $str2) use ($germanComp) { return $germanComp->compare($str1, $str2); };
 *
 */

class GermanComparator extends Collator {

   public function __construct()
   {
       parent::__construct('de_DE');
   }

   public function __invoke(string $str1, string $str2)
   {
       return $this->compare($str1, $str2); 
   }
}
