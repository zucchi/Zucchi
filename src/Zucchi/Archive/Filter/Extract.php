<?php
namespace Zucchi\Archive\Filter;

use Zucchi\Archive\AdapterAwareTrait;
use Zucchi\Archive\Adapter;
use Zend\Filter\AbstractFilter;

class Extract extends AbstractFilter
{
    use AdapterAwareTrait;
    
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
        $adapter = $this->getAdapter($value);
        $adapter->setArchive($value);
        $result = $adapter->decompress();
        return $result;
    }
}