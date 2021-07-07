TODO
====

Get expanded definitions from output-pdf.txt. For most lines the definition immediately follows the infinitive. For those lines where it doesn't, add it!
So we will NOT rely on the definitions in dict.php, in $dict[]. Instead we will rewrite or overwrite them. For Prefix Verbs, we already are getting the 
definitions from output-pdf.txt and no change is needed..

Resources:
---------

`Character Encoding for PHP Developers: Unicode, UTF-8 and ASCII <https://www.honeybadger.io/blog/php-character-encoding-unicode-utf8-ascii/>`_
`regex and unicode <ttps://www.regular-expressions.info/unicode.html>`_


What info from results.txt do I want as flashcards?

I. Definitions of all verbs, including prefix verbs, and Conjugations (of only strong and irregular verbs).

 create-conjugated.php creates verbs-2-conjugate.txt.

- I want the definition in a consistent, simple form like that in verbs.txt. Use either the definition in the index, which are one word; or make the results.txt
  definitions consistent and simpler.

II.
  Cloze samples sentences with translations.

- I want the sample sentences as cloze sentences in which the verb is replaced with ___________, and the translation of the sentence is in parentheses after 
  the German sentence. An the answer is the German verb correctly conjugated.
