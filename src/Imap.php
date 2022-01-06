<?php declare(strict_types=1);

namespace Jtrw\Imap;

use Jtrw\Imap\Exceptions\ImapConnectionException;

class Imap implements ImapInterface
{
    protected $connection;
    
    protected string $userName;
    protected string $password;
    protected string $serverName;
    protected string $flag;
    protected string $mailboxName;
    protected int $port;
    
    /**
     * @param string $userName
     * @param string $password
     * @param string $serverName
     * @param string $flag
     * @param string $mailboxName
     * @param int $port
     * @throws ImapConnectionException
     */
    public function __construct(string $userName, string $password, string $serverName, string $flag = "/imap/ssl/novalidate-cert", string $mailboxName = "INBOX", int $port = 993)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->serverName = $serverName;
        $this->port = $port;
        $this->flag = $flag;
        $this->mailboxName = $mailboxName;
        
        $this->connection();
    } // end __construct
    
    /**
     * @return void
     * @throws ImapConnectionException
     */
    protected function connection(): void
    {
        $connectStr = sprintf("{%s%s}%s", $this->serverName, $this->flag, $this->mailboxName);
        
        $this->connection = imap_open($connectStr, $this->userName, $this->password);
        if (!$this->connection) {
            throw new ImapConnectionException("Can't connect to '$this->serverName': ".imap_last_error());
        }
    } // end connection
    
    /**
     * @return int
     */
    public function getCountMessages(): int
    {
        return imap_num_msg($this->connection);
    } // end getCountMessages
    
    /**
     * @return int
     */
    public function getCountRecentMessages(): int
    {
        return imap_num_recent($this->connection);
    } // end getCountRecentMessages
    
    /**
     * @return ResponseDto[]
     */
    public function getAllInboxMails(): array
    {
        $msgCount = imap_num_msg($this->connection);
    
        $inbox = [];
        for($index = 0; $index <= $msgCount; $index++) {
            $inbox[] = $this->getMessageByIndex($index);
        }
        
        return $inbox;
    } // end getAllInboxMails
    
    /**
     * @param int $index
     * @return ResponseDto
     */
    public function getMessageByIndex(int $index): ResponseDto
    {
        $header = imap_headerinfo($this->connection, $index) ?: null;
        $body = imap_body($this->connection, $index) ?: null;
        $structure = imap_fetchstructure($this->connection, $index) ?: null;
        $uuid = imap_uid($this->connection, $index);
        $files = [];

        $this->parseAttachments($index, $structure->parts, "", $files);
        
        return new ResponseDto($index, $uuid, $header, $body, $structure, $files);
    } // end getMessageByIndex
    
    /**
     * @param int $index
     * @param array $parts
     * @param string $parentSection
     * @param array $files
     * @return void
     */
    protected function parseAttachments(int $index, array $parts, string $parentSection = "", array &$files = []): void
    {
        foreach ($parts as $subsection => $part){
            $section = $parentSection . ($subsection + 1);
            if (isset($part->parts)) {
                $this->parseAttachments($index, $part->parts, $section . ".");
            } elseif (isset($part->disposition)) {
                if (in_array(strtolower($part->disposition), ['attachment'])) {
                    $data = imap_fetchbody($this->connection, $index, $section);

                    $fileName = $part->parameters[1]->value ?? "attach-file";
                    $files[] = [
                        'filename' => $fileName,
                        'data'     => $data
                    ];
                }
            }
        }
    } // end parseAttachments
    
    /**
     * @param string $uid
     * @return int
     */
    public function getIndexByUuid(string $uid): int
    {
        return imap_msgno($this->connection, $uid);
    } // end getIndexByUuid
    
    /**
     * @return ResponseDto|null
     */
    public function getLastInboxMessage(): ?ResponseDto
    {
        $msgCount = $this->getCountMessages();
        
        if (!$msgCount) {
            return null;
        }
        
        return $this->getMessageByIndex($msgCount);
    } // end getLastInboxMessage
    
    /**
     * @return ResponseDto|null
     */
    public function getRecentInboxMessage(): ?ResponseDto
    {
        $msgCount = $this->getCountRecentMessages();
        
        if (!$msgCount) {
            return null;
        }

        return $this->getMessageByIndex($msgCount);
    } // end getRecentInboxMessage
    
    /**
     * @param string $uuid
     * @return void
     */
    public function removeMessageByUuid(string $uuid): void
    {
        $this->removeMessage($this->getIndexByUuid($uuid));
    } // end removeMessageByUuid
    
    /**
     * @param int $messageNumber
     * @return void
     */
    public function removeMessage(int $messageNumber): void
    {
        imap_delete($this->connection, $messageNumber);
        imap_expunge($this->connection);
    } // end removeMessage
    
    
    /**
     * @param int $messageIndex
     * @param string $folder
     * @return void
     */
    public function moveMessage(int $messageIndex, string $folder = 'INBOX.Processed'): void
    {
        imap_mail_move($this->connection, $messageIndex, $folder);
        imap_expunge($this->connection);
    } // end moveMessage
    
    /**
     *
     */
    public function __destruct()
    {
        imap_close($this->connection);
    } // end __destruct
}