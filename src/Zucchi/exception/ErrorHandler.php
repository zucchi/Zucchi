<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Exception;
/**
 * ErrorHandler - Class Description
 *
 * @author David Seward <dave@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Exception
 * @category Components
 */

class ErrorHandler
{
    public static function handleError($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null){

        if ($errstr !== null) {

            if (empty($errstr)) {
                $errstr = 'Unknown error';
            }


            $e = new ErrorException($errstr);
            $e->setNo($errno);
            $e->setFile($errfile);
            $e->setLine($errline);


            throw $e;
        }
    }


}