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
    /**
     * Debug helper function.  This is a wrapper for var_dump() that adds
     * the <pre /> tags, cleans up newlines and indents, and runs
     * htmlentities() before output.
     *
     * @param  mixed  $var   The variable to dump.
     * @param  string $label OPTIONAL Label to prepend to output.
     * @param  bool   $echo  OPTIONAL Echo output if true.
     * @return string
     */
    public static function dump($var, $label=null, $echo=true)
    {
        // format the label
        $label = ($label===null) ? '' : rtrim($label) . ' ';

        // var_dump the variable into a buffer and keep the output
        ob_start();
        \var_dump($var);
        $output = ob_get_clean();

        // neaten the newlines and indents
        
        if (self::getSapi() == 'cli') {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = PHP_EOL . $label
                    . PHP_EOL . $output
                    . PHP_EOL;
        } else {
            $output = $label . $output;
            
            if (!extension_loaded('xdebug')) {
                $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
                $output = htmlspecialchars($output, ENT_QUOTES);
                $output = '<pre>'. $output . '</pre>';
            }
        }

        if ($echo) {
            echo($output);
        }
        return $output;
    }
    
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