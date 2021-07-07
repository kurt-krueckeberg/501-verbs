Bugs & Design
=============

Unicode-relate problems:
------------------------
   
binary search evidently doesn't work--and maybe the string functions (not sure about preg_match)--because of Unicode!?
Does preg_match work correctly with unicode strings? See:

* `Article about PHP 7 and Unicode  <https://alanstorm.com/php-and-unicode/>`_
* See the 'u' regex flag to do unicode regex comparisons: '/regex_here/u'
 
Solution for binary search: see new-bsearch.php
    
Then use the solution in pdf-extractor.php and insert-definitions.php.

Resources:
---------

`regex and unicode <ttps://www.regular-expressions.info/unicode.html>`_

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
