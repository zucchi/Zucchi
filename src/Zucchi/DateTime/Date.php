<?php
namespace Zucchi\DateTime;

class Date extends DateTime implements
    \JsonSerializable
{
    public function __toString()
    {
        return $this->format(self::DATE);
    }
}