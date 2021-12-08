# PHP Imap

## Example 
```php
$imap = new \Jtrw\Imap\Imap("login", "pass", "imap.ukr.net");

$message = $imap->getLastInboxMessage();

$files = $message->getFiles();

$imap->removeMessageByUuid($message->getUuid());

```

## Interface Imap
```php
<?php

namespace Jtrw\Imap;

/**
 *
 */
interface ImapInterface
{
    /**
     * @return int
     */
    public function getCountMessages(): int;
    
    /**
     * @return int
     */
    public function getCountRecentMessages(): int;
    
    /**
     * @return ResponseDto[]
     */
    public function getAllInboxMails(): array;
    
    /**
     * @param int $index
     * @return ResponseDto
     */
    public function getMessageByIndex(int $index): ResponseDto;
    
    /**
     * @param string $uid
     * @return int
     */
    public function getIndexByUuid(string $uid): int;
    
    /**
     * @return ResponseDto|null
     */
    public function getLastInboxMessage(): ?ResponseDto;
    
    /**
     * @return ResponseDto|null
     */
    public function getRecentInboxMessage(): ?ResponseDto;
    
    /**
     * @param string $uuid
     * @return void
     */
    public function removeMessageByUuid(string $uuid): void;
    
    /**
     * @param int $messageNumber
     * @return void
     */
    public function removeMessage(int $messageNumber): void;
    
    /**
     * @param int $messageIndex
     * @param string $folder
     * @return void
     */
    public function moveMessage(int $messageIndex, string $folder = 'INBOX.Processed'): void;
}
```