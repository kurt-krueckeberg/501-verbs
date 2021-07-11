<?php
declare(strict_types = 1);

namespace App\Intl;
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
