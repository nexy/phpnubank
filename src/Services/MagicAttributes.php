<?php
namespace Nubank\Services;

use Nubank\Exceptions\NuException;

trait MagicAttributes
{
    private $attribs = [];

    public function __set($key, $value)
    {
        $this->attribs[$key] = $value;
        
        return $this;
    }

    public function __get($key)
    {
        if (!isset($this->attribs[$key])) {
            throw new NuException("Url {$key} is not here");
        }

        return $this->attribs[$key];
    }

    public function attributes()
    {
        return $this->attribs;
    }
}