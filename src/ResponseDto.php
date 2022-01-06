<?php declare(strict_types=1);

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
    } // end __construct
    
    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    } // end getIndex
    
    /**
     * @return stdClass
     */
    public function getHeader(): stdClass
    {
        return $this->header;
    } // end getHeader
    
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    } // end getBody
    
    /**
     * @return stdClass
     */
    public function getStructure(): stdClass
    {
        return $this->structure;
    } // end getStructure
    
    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    } // end getUuid
    
    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    } // end getFiles
}