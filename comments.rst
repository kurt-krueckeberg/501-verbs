Bugs & Design
=============

Unicode-relate problems:
------------------------
   
binary search evidently doesn't work--and maybe the string functions (not sure about preg_match)--because of Unicode!?
Does preg_match work correctly with unicode strings? See:

* `Article about PHP 7 and Unicode  <https://alanstorm.com/php-and-unicode/>`_
* See the 'u' regex flag to do unicode regex comparisons: '/regex_here/u'
 
Solution for binary search:

Option 1: Use the Collator class to compare strings and to sort arrays, or use collator__create/compare methods, and pass it as a lambda to binary_search

Option 2: Create a function object using a class with a binary __invoke($str1, $str2) method. This mimics the C++ binary_search() template with a binary
predicate object. 

.. code-block:: php

    <?php
    declare(strict_types = 1);

     // new algorithms.php code    
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

    // new binary predicate class
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
   
     // test case 
    $germanComp = new GermanComparator();

    $keys = array_keys($verbs);
    
    shuffle($keys);
    
    $germanComp->sort($keys);
    
    print_r($keys);
    
Then use the solution in pdf-extractor.php and insert-definitions.php.

Resources:
----------

`PHP new support for closures explained <https://www.brainbell.com/php/closures.html>`_.

Design:

What info from results.txt do I want as flashcards?

I. Definitions of all verbs, including prefix verbs, and Conjugations (of only strong and irregular verbs).

 create-conjugated.php creates verbs-2-conjugate.txt.

- I want the definition in a consistent, simple form like that in verbs.txt. Use either the definition in the index, which are one word; or make the results.txt
  definitions consistent and simpler.

II.
  Cloze samples sentences with translations.

- I want the sample sentences as cloze sentences in which the verb is replaced with ___________, and the translation of the sentence is in parentheses after 
  the German sentence. An the answer is the German verb correctly conjugated.
