<?php
namespace Zucchi\DateTime;

class Date extends DateTime
{
    public function __toString()
    {
        return $this->format(self::DATE);
    }
}