<?php
use App\File\FileObject;

require_once './boot-strap/boot-strap.php';

boot_strap();

require_once "./must-conjugate.php";
require_once "./algorithms.php";

$ifile = new FileObject("./results.txt", "r");
$rfile = new FileObject("./verbs-2-conjugate.txt", "w");


foreach($ifile as $lineno => $line) {

   if (0 === (strpos($line, "SEP")) || 0 === (strpos($line, "INS")))
          continue;

    $parts = explode(" | ", $line);

    if (binary_search($must_conjugate, $parts[0])) {

            $output = $parts[0] . '|' . $parts[1] . "\n";

            $rfile->fwrite($output);
    }
}
