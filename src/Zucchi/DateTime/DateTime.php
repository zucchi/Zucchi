<?php
namespace Zucchi\DateTime;

class DateTime extends \DateTime
{
    const MYSQL = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';
    const TIME = 'H:i:s';
    
    public function __toString()
    {
        return $this->format(self::MYSQL);
    }
    
    static public function createFromFormat($format, $time, $object = null)
    {
        $ext_dt = new self();

        $ext_dt->setTimestamp(parent::createFromFormat($format, $time)->getTimestamp());

        return $ext_dt;
    }
}