<?php

namespace Jtrw\Imap;

use Jtrw\Imap\Exceptions\ImapProviderNotFound;

class ImapFactory
{
    public const IMAP_UKR_NET = "imap.ukr.net";
    public const IMAP_GMAIL = "imap.gmail.com";
    
    public static function factory(string $providerName, string $userName, string $password)
    {
        switch ($providerName) {
        case 'ukr':
            $imapServer = static::IMAP_UKR_NET;
            break;
        case 'gmail':
            $imapServer = static::IMAP_GMAIL;
            break;
        default:
            throw new ImapProviderNotFound("Provider {$providerName} Not Found");
        }
        
        return new Imap($userName, $password, $imapServer);
        
    }
}