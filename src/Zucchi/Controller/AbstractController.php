<?php
/**
 * Zucchi (http://framework.zend.com/)
 *
 * @link      http://github.com/zucchi/Zucchi for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Zucchi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

/**
 * Abstract controller to provide base for admin functoinality in Zucchi Modules
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Controller
 * @category Components
 */
abstract class AbstractController extends AbstractRestfulController
{
    /**
     * array of layouts to nest view in
     * @var array
     */
    public $nestedViews = array();
    
    /**
     * Messages to pass to view
     * @var array
     */
    protected $messages = array();
    
    /**
     * default constructor
     */
    public function __construct()
    {
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $this->messages = $flashMessenger->getMessages();
        }
        
    }
    
    /**
     * Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        
    }
    
    
    /**
     * load the specified view script
     * @param string $viewScript
     * @param array $variables
     * @param section $section
     * @return \Zend\View\Model\ViewModel
     */
    
    protected function loadView($viewScript, $variables = array())
    {
        // set the view
        $view = new ViewModel($variables);
        $view->setTemplate($viewScript);
        
        // handle nested view scripts
        foreach ($this->nestedViews as $viewSpec) {
            $nested = new ViewModel();
            $nested->setTemplate($viewSpec);
            $nested->addChild($view,'content');
            $view = $nested;
        }
        
        $this->layout()->setVariable('messages', $this->messages);
        
        return $view;
        
    }
}
