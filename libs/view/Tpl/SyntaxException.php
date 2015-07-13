<?php

namespace libs\view\Tpl;

/**
 * Exception thrown when syntax error occurs.
 */
class SyntaxException extends Exception
{
    public function __construct($message, $code, Exception $previous = null, $line = null, $file = null)
    {
        parent::__construct($message, $code, $previous);

        if ($line) $this->line = $line;
        if ($file) $this->file = $file;
    }


    /**
     * Line in template file where error has occured.
     *
     * @var int | null
     */
    protected $templateLine = null;

    /**
     * Tag which caused an error.
     *
     * @var string | null
     */
    protected $tag = null;

    /**
     * Handles the line in template file
     * where error has occured
     *
     * @param int | null $line
     *
     * @return libs\view\Tpl\SyntaxException | int | null
     */
    public function templateLine($line){
        if(is_null($line))
            return $this->templateLine;

        $this->templateLine = (int) $line;
        return $this;
    }

    /**
     * Handles the tag which caused an error.
     *
     * @param string | null $tag
     *
     * @return libs\view\Tpl_SyntaxException | string | null
     */
    public function tag($tag=null){
        if(is_null($tag))
            return $this->tag;

        $this->tag = (string) $tag;
        return $this;
    }
}

// -- end
