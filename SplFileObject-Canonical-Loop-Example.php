<?php
use App\File\SplFileObjectExtended;

require_once "./boot-strap/boot-strap.php";
boot_strap();
 
if ($argc == 1) {
   echo "Enter the name of the file to check.\n";
   return;
}


$file = new SplFileObjectExtended("test.html", "r");

foreach ($file as $data) {

  $line = trim($data);

} 
