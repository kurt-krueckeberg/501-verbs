<?php

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

function binary_search_(array $a, $first, $last, $key, $comparator)
{
    $lo = $first; 
    $hi = $last - 1;

    while ($lo <= $hi) {

        $mid = (int)(($hi - $lo) / 2) + $lo;
   
        $cmp = $compartor($a[$mid], $key);

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

function binary_search(array $a, $key, $comparator)
{
  return binary_search_($a, 0, count($a), $key, 'strcmp');
}
