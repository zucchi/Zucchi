<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zucchi\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Truncate input text
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
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
