<?php
/**
 * Image.php - Image
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Image;

/**
 * Image - Class Description
 *
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package
 * @subpackage
 * @category
 */
class Image implements
    \JsonSerializable
{
    /**
     * The filename of the image
     * @var string
     */
    public $name;

    /**
     * the uri for the image
     * @var string
     */
    public $uri;

    /**
     * The filesystem path for the image
     * @var string
     */
    public $path;

    /**
     * The image mimetype
     * @var string
     */
    public $type;

    /**
     * Alt string
     * @var string
     */
    public $alt;

    /**
     * the size of the file
     * @var integer
     */
    public $size;

    /**
     * height of the image
     * @var integer
     */
    public $height;

    /**
     * width of the image
     * @var integer
     */
    public $width;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Parameter provided to %s must be an array or Traversable',
                __METHOD__
            ));
        }

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } else if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function __toString()
    {
        if ($file = $this->getName()) {
            return $file;
        }
        return '';
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * set the file
     * @param string $file
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * get the file
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set the file
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $path = realpath($path);

        $this->path = $path;
        return $this;
    }

    /**
     * set the filesystem path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * set the Uri
     * @param string $uri
     * @return Image
     */
    public function setUri($uri)
    {
        $uri = trim($uri,'/');

        $this->uri = '/' . $uri;
        return $this;
    }

    /**
     * get the uri
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * set the MimeType
     * @param string $mimetype
     * @return Image
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * get the mimetype
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * set the alt for the image
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }

    /**
     * get the alt
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * set the size for the image
     * @param string $alt
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * get the size
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * set the width
     * @param int $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * get the height
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * set the width
     * @param int $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * get the width
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }


}
