<?php

include 'vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('./501 German Verbs.pdf');
 
// Retrieve all pages from the pdf file.
$pages  = $pdf->getPages();

$ofile = fopen("./new-output.txt", 'w');
 
// Loop over each page to extract text.
$count = 0;

foreach ($pages as $page) {

    ++$count;

    $page_text = $page->getText();

    $output = "PAGE_START\n" . $page_text . "\nPAGE_END\n";          
    fwrite($ofile, $output);
}
