#!/usr/bin/env php

<?php
use App\File\FileObject;

require_once "./boot-strap/boot-strap.php";
boot_strap();
 
function get_line($file)
{
   if (!$file->eof()) {

     $line = $file->fgets();

     return trim($line);
  } 
  
  return '';
}

// append 'u' to regex if the $str is encoded as utf-8.
function preg_match_u($regex, $str, array &$matches = null) 
{
     $regex .= (strpos(mb_detect_encoding($str), "UTF") === 0) ? 'u' : ''; 

     return preg_match($regex, $str, $matches);
}

if ($argc != 3) {
  echo "Input: File_name followed by regex\n";
  return;
}

$file_in = $argv[1];

$regex = '/' . $argv[2] . '/';

$file = new FileObject($file_in, "r");
$line_no = 0; 

// TOD: Detect if the input is UTF-8 -- convert it?
// mb_detect_encoding AND mb_convert_encoding
while ($line = get_line($file) != '') {

     $line_no++; 
     $matches = array();  

     if (preg_match_u($regex, $line, $matches)) {

          echo "Line " . $line_no . ": " . $matches[0];
     }
}
