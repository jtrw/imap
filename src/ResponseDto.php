<?php

namespace Jtrw\Imap;

use stdClass;

class ResponseDto
{
    private int $index;
    private string $uuid;
    private StdClass $header;
    private string $body;
    private stdClass $structure;
    
    public function __construct(int $index, string $uuid, StdClass $header, string $body, stdClass $structure)
    {
        $this->index = $index;
        $this->header = $header;
        $this->body = $body;
        $this->structure = $structure;
        $this->uuid = $uuid;
    }
    
    public function getIndex(): int
    {
        return $this->index;
    }
    
    public function getHeader(): stdClass
    {
        return $this->header;
    }
    
    public function getBody(): string
    {
        return $this->body;
    }
    
    public function getStructure()
    {
        return $this->structure;
    }
    
    public function getUuid(): string
    {
        return $this->uuid;
    }
}