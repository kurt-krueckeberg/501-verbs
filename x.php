<?php
use App\File\SplFileObjectExtended;

require_once "./boot-strap/boot-strap.php";
boot_strap();

 
$ifile = new SplFileObjectExtended("./output-pdf.txt", "r");

var_dump($ifile);
