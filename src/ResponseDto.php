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
    private array $files;
    
    /**
     * @param int $index
     * @param string $uuid
     * @param stdClass $header
     * @param string $body
     * @param stdClass $structure
     * @param array $files
     */
    public function __construct(int $index, string $uuid, StdClass $header, string $body, stdClass $structure, array $files)
    {
        $this->index = $index;
        $this->header = $header;
        $this->body = $body;
        $this->structure = $structure;
        $this->uuid = $uuid;
        $this->files = $files;
    }
    
    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }
    
    /**
     * @return stdClass
     */
    public function getHeader(): stdClass
    {
        return $this->header;
    }
    
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
    
    /**
     * @return stdClass
     */
    public function getStructure()
    {
        return $this->structure;
    }
    
    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
    
    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}