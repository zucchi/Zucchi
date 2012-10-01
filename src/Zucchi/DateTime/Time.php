<?php
namespace Zucchi\DateTime;

class Time extends DateTime
{
    public function __toString()
    {
        return $this->format(self::DATE);
    }
}