<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiAdmin for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace Zucchi\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Truncate input text
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage View
 * @category Helper
 */
class Truncate extends AbstractHelper
{
    /**
     * Truncate input text
     *
     * @param string $text
     * @param int $length
     * @param bool $wordsafe
     * @param bool $escape
     * @return string
     */
    public function __invoke($text, $length, $wordsafe = true, $escape = true)
    {
        if (strlen($text) <= $length)
            return $escape ? $this->view->escapeHtml($text) : $text;
        
        if (!$wordsafe) {
            $text = substr($text, 0, $length);
        } else {
            $text   = substr($text, 0, $length + 1);
            $length = strrpos($text, ' ');
            $text   = substr($text, 0, $length);
            
            preg_match('/(.*?)(?:[^a-zA-Z0-9])*$/', $text, $match);
            $text = $match[1];
        }
        
        return ($escape ? $this->view->escapeHtml($text) : $text) . '&hellip;';
    
    }
}
