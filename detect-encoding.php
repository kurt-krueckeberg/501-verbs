#!/usr/bin/env php
<?php

if ($argc != 2) {
   echo "Enter the name of the file to check.\n";
   return;
}

$file = fopen($argv[1], "r");

if ($file === false) {

  echo "Could not open file " . $argv[1] . "\n";
  return;
}

while(!feof($file)) {

  $line = fgets($file);
  $str = trim($line);
  $encoding = mb_detect_encoding($str, ['UTF-8', 'ISO-8859-1', 'ISO-8859-5']);

  echo $encoding . "\n";
}
