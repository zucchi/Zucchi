<?php
namespace Zucchi\Archive\Validator;

use Zucchi\Archive\AdapterAwareTrait;
use Zend\Validator\AbstractValidator;

class Contains extends AbstractValidator
{
    use AdapterAwareTrait;
    
    
    const NOT_FOUND    = 'notFound'; 
    
    protected $messageTemplates = array(
        self::NOT_FOUND         => "The archive does not contain one or more of the required files",
    );
    
    protected $files = array();
    
    public function setFiles($files)
    {
        if (is_string($files)) {
            $files = array($files);
        }
        $this->files = $files;
        return $this;
    }
    
    public function getFiles()
    {
        return $this->files; 
    }
    
    public function isValid($value)
    {
        
        $adapter = $this->getAdapter($value);
        $adapter->setArchive($value);
        $contents = $adapter->listContent();
        $find = $this->getFiles();
        
        $found = array();
        foreach ($contents as $c) {
            $found[] = $c['filename'];
        }
        
        $valid = true;
        foreach ($find as $file) {
            if (!in_array($file, $found)) {
                $valid = false;
            }
        } 
        
        $this->error(self::NOT_FOUND);
        
        return $valid;
    }
}
