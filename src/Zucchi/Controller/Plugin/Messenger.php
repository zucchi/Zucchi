<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiAdmin for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace Zucchi\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
/**
 * Controller plugin to handling the storage and passing of messages for output
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Controller
 * @category Plugin
 */
class Messenger extends AbstractPlugin implements 
   \Countable, 
    \Iterator,
    \ArrayAccess
{
    /**
     * The internal pointer for traversal
     * @var integer
     */
    private $position = 0;
    
    /**
     * the array containing the messages
     * @var array
     */
    protected $messages = array();
    
    /**
     * the constructor
     */
    public function __construct()
    {
        $this->position = 0;
    }
    
    /**
     * allow passing of a message on invokation
     * @param string|array$message
     * @param string $status
     * @param string $title
     * @param bool $dismissable
     * @return \Zucchi\Controller\Plugin\Messenger
     */
    public function __invoke($message = null, $status = 'block', $title = null, $dismissable = true)
    {
        if ($message) {
            $this->addMessage($message, $status, $title, $dismissable);
        }
        return $this;
    }
    
    /**
     * Add a message
     * @param string|array $message
     * @param string $status
     * @param string $title
     * @param bool $dismissable
     * @return \Zucchi\Controller\Plugin\Messenger
     */
    public function addMessage($message, $status = 'block', $title = null, $dismissable = true)
    {
        $this->messages[] = array(
            'message'     => $message,
            'status'      => $status,
            'title'       => $title,
            'dismissable' => (bool) $dismissable,
        );
        return $this;
    }
    
    /**
     * Add messages from an array
     * @param array|Traversable $messages
     * @return \Zucchi\Controller\Plugin\Messenger
     */
    public function addMessages($messages)
    {
        if ($messages) {
            foreach ($messages as $message) {
                $title = false;
                $status = 'block';
                $dismissable = true;
                if (is_array($message)) {
                    if (isset($message['title'])) {
                        $title = $message['title'];
                        unset($message['title']);
                    }
                    if (isset($message['status'])) {
                        $status = $message['status'];
                        unset($message['status']);
                    }
                    if (isset($message['dismissable'])) {
                        $dismissable = $message['dismissable'];
                        unset($message['dismissable']);
                    }
                    if (isset($message['message'])) {
                        $message = $message['message'];
                    }
                }
                $this->addMessage($message, $status, $title, $dismissable);
            }
        }
        return $this;
    }
    
    /**
     * get the messages
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * clear all the messages
     * @return \Zucchi\Controller\Plugin\Messenger
     */
    public function clearMessages()
    {
        $this->rewind();
        $this->messages = array();
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->messages);
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->messages[$this->position];
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }
    
    /**
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid()
    {
        return isset($this->messages[$this->position]);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->messages[] = $value;
        } else {
            $this->messages[$offset] = $value;
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset) 
    {
        return isset($this->messages[$offset]);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) 
    {
        unset($this->messages[$offset]);
    }
    
    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) 
    {
        return isset($this->messages[$offset]) ? $this->messages[$offset] : null;
    }
}