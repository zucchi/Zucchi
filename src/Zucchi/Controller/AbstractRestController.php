<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/Zucchi for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace Zucchi\Controller;

use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
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
abstract class AbstractRestController extends AbstractRestfulController
{
    /**
     * array of layouts to nest view in
     * @var array
     */
    public $nestedViews = array();
    
    /**
     * REST method: Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        exit('GET REST interface not implemented');
    }

    /**
     * REST method: Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        exit('GET REST interface not implemented');
    }

    /**
     * REST method: Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        exit('POST REST interface not implemented');
    }

    /**
     * REST method: Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        exit('PUT REST interface not implemented');
    }

    /**
     * REST method: Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        exit('DELETE REST interface not implemented');
    }
    
    
    /**
     * load the specified view script
     * @param string $viewScript
     * @param array $variables
     * @param section $section
     * @return \Zend\View\Model\ViewModel
     */
    protected function loadView($viewScript = null, $variables = array())
    {
        // set the view
        $view = new ViewModel($variables);
        
        // apply selected view script
        if ($viewScript) {
            $view->setTemplate($viewScript);
        }
        
        // handle nested view scripts
        foreach ($this->nestedViews as $viewSpec) {
            $nested = new ViewModel();
            $nested->setTemplate($viewSpec);
            $nested->addChild($view,'content');
            $view = $nested;
        }
        
        $messenger = $this->messenger()->addMessages($this->flashMessenger()->getMessages());
        $this->layout()->setVariable('messages', $messenger);
        
        return $view;
    }
}
