<?php
 $str = 'abholen | PP: abholen, holt ab, holte ab, abgeholt | ,,Ich wollte das Paket abholen aber sie hatte es schon abgeholt.“';
 echo $str . "\n";

 $examples = preg_replace('/,,/', '„', $str);
 echo $examples . "\n";
