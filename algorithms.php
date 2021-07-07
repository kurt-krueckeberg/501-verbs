<?php
declare(strict_types = 1);

/*
 * $comparator can be either a lambda or a binary predicate object.
 * Note: Type 'object' can be used in place of 'callable' without code breaking.
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
