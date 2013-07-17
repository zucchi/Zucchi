<?php
namespace Zucchi\DateTime;

class DateTime extends \DateTime implements
    \JsonSerializable
{
    const MYSQL = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';
    const TIME = 'H:i:s';
    
    public function __toString()
    {
        return $this->format(self::MYSQL);
    }

    /**
     * (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->__toString();
    }

    public function toJson()
    {
        return $this->__toString();
    }
    
    static public function createFromFormat($format, $time, $object = null)
    {
        $class = get_called_class();
        $ext_dt = new $class;

        $parent = \DateTime::createFromFormat($format, $time);

        if ($parent) {
            $timestamp = $parent->getTimestamp();
            $ext_dt->setTimestamp($timestamp);
        }

        return $ext_dt;
    }
}