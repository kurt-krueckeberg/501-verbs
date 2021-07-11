<?php
use App\File\SplFileObjectExtended;

require_once './boot-strap.php';

boot_strap();

$file = new SplFileObjectExtended("./results.txt", "r");
var_dump($file);
