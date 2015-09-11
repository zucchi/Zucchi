<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/Zucchi for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace Zucchi\Debug;

use Zend\Debug\Debug as ZendDebug;

/**
 * Custom Debug Utility
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Debug
 * @category Components
 */
class Debug extends ZendDebug
{
    public static function globals()
    {
        if (!extension_loaded('xdebug')) {
            throw new \RuntimeException('XDebug must be installed to use this function');
        }
        
        \xdebug_dump_superglobals();
    }
    
    
    public static function stack()
    {
        if (!extension_loaded('xdebug')) {
            throw new \RuntimeException('XDebug must be installed to use this function');
        }
        
        \xdebug_print_function_stack();
    }
    
    public static function startTrace($file = 'trace', $html = true)
    {
        if (!extension_loaded('xdebug')) {
            throw new \RuntimeException('XDebug must be installed to use this function');
        }
        $flag = ($html) ? XDEBUG_TRACE_HTML : XDEBUG_TRACE_COMPUTERIZED;
        \xdebug_start_trace($file, $flag);
    }
    
    public static function stopTrace()
    {
        if (!extension_loaded('xdebug')) {
            throw new \RuntimeException('XDebug must be installed to use this function');
        }
        \xdebug_stop_trace();
    }

    public function breakHere($condition = true)
    {
        if (!extension_loaded('xdebug')) {
            throw new \RuntimeException('XDebug must be installed to use this function');
        }

        if ((is_callable($condition) && $condition()) || $condition) {
            \xdebug_break();
        }
    }
}