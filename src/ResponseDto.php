<?php

namespace Jtrw\Imap;

use stdClass;

class ResponseDto
{
    private int $index;
    private StdClass $header;
    private string $body;
    private stdClass $structure;
    
    public function __construct(int $index, StdClass $header, string $body, stdClass $structure)
    {
        $this->index = $index;
        $this->header = $header;
        $this->body = $body;
        $this->structure = $structure;
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
}