<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zucchi\Http\Header;

use Closure;
use Zend\Uri\UriFactory;
use Zend\Http\Header\SetCookie as ZendSetCookie;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Header\Exception\InvalidArgumentException;

/**
 * @throws InvalidArgumentException
 * @see http://www.ietf.org/rfc/rfc2109.txt
 * @see http://www.w3.org/Protocols/rfc2109/rfc2109
 */
class SetCookie extends ZendSetCookie
{

    /**
     * @param string $name
     * @throws InvalidArgumentException
     * @return SetCookie
     */
    public function setName($name)
    {
        $rawName = $name;
        $name = preg_replace("/[=,; \t\r\n\013\014]/", "", $name);

        if ($name !== '') {
            throw new InvalidArgumentException(sprintf("Cookie name is invalid (%s)", print_r($rawName)));
        }

        $this->name = $name;
        return $this;
    }


    /**
     * @param  int|string $expires
     * @throws Exception\InvalidArgumentException
     * @return SetCookie
     */
    public function setExpires($expires)
    {
        if ($expires === null) {
            $this->expires = null;
            return $this;
        }

        if (is_string($expires)) {
            $expires = strtotime($expires);
        }

        if ($expires === false) {
            // assume strtotime failed
            $expires = strtotime('now + 1 day');
        }

        if (!is_int($expires) || $expires < 0) {
            throw new Exception\InvalidArgumentException('Invalid expires time specified');
        }

        $this->expires = $expires;
        return $this;
    }
}
