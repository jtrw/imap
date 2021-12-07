<?php
namespace Jtrw\Imap;

use Jtrw\Imap\Exceptions\ImapConnectionException;

class Imap
{
    protected $connection;
    
    protected string $userName;
    protected string $password;
    protected string $serverName;
    protected string $flag;
    protected string $mailboxName;
    protected int $port;
    
    public function __construct(string $userName, string $password, string $serverName, string $flag = "/imap/ssl/novalidate-cert", string $mailboxName = "INBOX", int $port = 993)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->serverName = $serverName;
        $this->port = $port;
        $this->flag = $flag;
        $this->mailboxName = $mailboxName;
        
        $this->connection();
    }
    
    protected function connection()
    {
        $connectStr = sprintf("{%s%s}%s", $this->serverName, $this->flag, $this->mailboxName);
        
        $this->connection = imap_open($connectStr, $this->userName, $this->password);
        if (!$this->connection) {
            throw new ImapConnectionException("Can't connect to '$this->serverName': ".imap_last_error());
        }
    }
    
    public function getCountMessages(): int
    {
        return imap_num_msg($this->connection);
    }
    
    public function getCountNewMessages(): int
    {
        return imap_num_recent($this->connection);
    }
    
    public function getAllInboxMails(): array
    {
        $msgCount = imap_num_msg($this->connection);
    
        $inbox = [];
        for($index = 0; $index <= $msgCount; $index++) {
            $inbox[] = $this->getMessaageByIndex($index);
        }
        
        return $inbox;
    }
    
    public function getMessaageByIndex(int $index): ResponseDto
    {
        $header = imap_headerinfo($this->connection, $index) ?: null;
        $body = imap_body($this->connection, $index) ?: null;
        $structure = imap_fetchstructure($this->connection, $index) ?: null;
        
        return new ResponseDto($index, $header, $body, $structure);
    }
    
    public function getLastInboxMessage()
    {
        $msgCount = $this->getCountMessages();
        
        return $this->getMessaageByIndex($msgCount);
    }
    
    public function getLastNewInboxMessage()
    {
        $msgCount = $this->getCountNewMessages();
        return $this->getMessaageByIndex($msgCount);
    }
    
    public function removeMessage(int $messageNumber): void
    {
        imap_delete($this->connection, $messageNumber);
        imap_expunge($this->connection);
    }
    
    public function moveMessage(int $messageIndex, string $folder = 'INBOX.Processed'): void
    {
        imap_mail_move($this->connection, $messageIndex, $folder);
        imap_expunge($this->connection);
    }
    
    public function __destruct()
    {
        imap_close($this->connection);
    }
}