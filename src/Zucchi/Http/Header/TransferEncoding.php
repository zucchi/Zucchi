<?php
/**
 * TransferEncoding.php - TransferEncoding
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Http\Header;

use Zend\Http\Header\HeaderInterface;

/**
 * Class TransferEncoding
 *
 * @author Rick Nicol <rick@zucchi.co.uk>
 * @package Zucchi\Http\Header
 */
class TransferEncoding implements HeaderInterface
{
    /**
     * Parse Header.
     *
     * @param $headerLine
     * @return static
     * @throws Exception\InvalidArgumentException
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.41
     */
    public static function fromString($headerLine)
    {
        $header = new static();

        list($name, $value) = explode(': ', $headerLine, 2);

        // Check to ensure this is the corrent header for the class.
        if (strtolower($name) !== 'transfer-encoding') {
            throw new Exception\InvalidArgumentException('Invalid header line for Transfer-Encoding string: "' . $name . '"');
        }

        $header->value = trim($value);

        return $header;
    }

    /**
     * Get Field Name.
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Transfer-Encoding';
    }

    /**
     * Get Field Value.
     *
     * @return string
     */
    public function getFieldValue()
    {
        return $this->value;
    }

    /**
     * Get class and value.
     *
     * @return string
     */
    public function toString()
    {
        return 'Transfer-Encoding: ' . $this->getFieldValue();
    }
}
