<?php
namespace Zucchi\Archive;

use Zucchi\Archive\Adapter;

trait AdapterAwareTrait
{
    /**
     * Compression adapter
     */
    protected $adapter;
    
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * @todo implement better way to auto load adapters
     * @param string $value 
     */
    public function getAdapter($value)
    {
        if (!$this->adapter) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($value);
            switch($mime) {
                case 'application/x-bzip':
                case 'application/x-bzip2':
                    $this->adapter = new Adapter\Tar();
                    $this->adapter->setMode('bz2');
                    break;
                case 'application/x-gzip':
                    $this->adapter = new Adapter\Tar();
                    $this->adapter->setMode('gz');
                    break;
                case 'application/x-tar':
                    $this->adapter = new Adapter\Tar();
                    break;
                case 'application/zip':
                    $this->adapter = new Adapter\Zip();
                    break;
                default:
                    throw new \RuntimeException(sprintf(
                        '%s Archive adapter detection for mime type "%s" is currently unsupported',
                        __METHOD__,
                        $mime
                    ));
                    break;
            }
        }
        return $this->adapter;
    }
}