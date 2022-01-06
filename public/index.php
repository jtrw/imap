<?php

require_once __DIR__."/../vendor/autoload.php";
//
//$arr = str_getcsv(file_get_contents(__DIR__.'/../test.csv'));
//print_r($arr);
//exit;
$imap = new \Jtrw\Imap\Imap("my_mail_post", "02xRgTb515zy5GES", "imap.ukr.net");

$message = $imap->getLastInboxMessage();

if (!$message) {
    die("NULL");
}
$files = $message->getFiles();

//$imap->removeMessageByUuid($message->getUuid());

foreach ($files as $file) {
    $fileStr = base64_decode($file['data']);
    
    $fileData = array_unique(explode("\n", $fileStr));
    
    $fileData = array_values(array_filter($fileData));
    
    unset($fileData[0]);

    foreach ($fileData as $row) {
        if (strlen($row) > 8) {
            echo $row."\n";
        }
    }
}







//var_dump($fileData[3]);

//foreach ($fileData as $key => $value) {
//    if (is_numeric($value)) {
//        continue;
//    }
//    echo "V= ".$value."\n";
//    unset($fileData[$key]);
//}

//print_r($fileData);
//exit;