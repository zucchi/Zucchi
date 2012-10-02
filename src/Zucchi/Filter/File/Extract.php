<?php
namespace Zucchi\Filter\File;

use Zend\Filter\Compress;

class Extract extends Compress
{
    
    /**
     * Compression adapter
     */
    protected $adapter;
    
    /**
     * Defined by Zend_Filter_Filter
     *
     * Decompresses the content $value with the defined settings
     *
     * @param  string $value Content to decompress
     * @return string The decompressed content
     */
    public function filter($value)
    {
        if (!$this->adapter) {
            // we need to detect the adapter to use
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($value);
            switch($mime) {
                case 'application/x-bzip':
                case 'application/x-bzip2':
                    $this->adapter = new \Zend\Filter\Compress\Tar(array('mode' => 'Bz2'));
                    break;
                case 'application/x-gzip':
                    var_dump('moo');
                    $this->adapter = new \Zend\Filter\Compress\Tar();
                    var_dump('cow');
                    break;
                case 'application/zip':
                    $this->adapter = new \Zend\Filter\Compress\Zip();
                    break;
                default:
//                     throw new \RuntimeException(sprintf(
//                         '%s unable to load adapter for mime type "%s"',
//                         __METHOD__,
//                         $mime
//                     ));
                    break;
            }
        }
        var_dump($mime);
        $result = $this->getAdapter()->decompress($value);
        var_dump($result);
        
        return $result;
    }
}