<?php
namespace Zucchi\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
 
class Messenger extends AbstractPlugin
{
    public function moo()
    {
        exit('moo')
    }
}