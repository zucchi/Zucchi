<?php
namespace Zucchi\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
 
class Messenger extends AbstractPlugin
{
    public function __invoke()
    {
        exit('moo')
    }
}