<?php

require_once __DIR__."/../vendor/autoload.php";

$imap = new \Jtrw\Imap\Imap("login", "***", "imap.ukr.net");

$message = $imap->getLastInboxMessage();

print_r($message);
exit;