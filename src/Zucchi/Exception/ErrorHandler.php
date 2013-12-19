<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Exception;

use Zend\Mail;

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
    public static $sendTo = 'dev@zucchi.co.uk';

    /**
     * catch errors and convert to exceptions
     *
     * @todo add severity handling to ignore notices and warnings
     * @param $errno
     * @param $errstr
     * @param null $errfile
     * @param null $errline
     * @param array $errcontext
     * @throws ErrorException
     */
    public static function handleError($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null)
    {
        if (0 != error_reporting()) {
            if (!$errstr) {
                $errstr = 'Unknown error';
            }
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
    }

    /**
     * Sends email error report.
     *
     * @param \Exception $e
     */
    static public function sendErrorReport(\Exception $e)
    {
        $emailBody = 'Error Report' . PHP_EOL
            . 'Exception Type: ' . get_class($e) . PHP_EOL;

        if ($e instanceof \ErrorException) {
            switch($e->getCode()) {
                case E_USER_ERROR:
                    $type = 'Fatal Error';
                    break;
                case E_USER_WARNING:
                    $type = 'User Warning';
                    break;
                case E_WARNING:
                    $type = 'Warning';
                    break;
                case E_USER_NOTICE:
                    $type = 'User Notice';
                    break;
                case E_NOTICE:
                    $type = 'Notice';
                    break;
                case E_STRICT:
                    $type = 'Strict';
                    break;
                case E_RECOVERABLE_ERROR:
                    $type = 'Catchable';
                    break;
                default:
                    $type = 'Unknown Error';
                    break;
            }

            $emailBody .= 'Error Type: '. $type. PHP_EOL;
        } else {
            $emailBody .= 'Error No: '. $e->getCode(). PHP_EOL;
        }

        $emailBody .= 'Line: ' . $e->getLine() . PHP_EOL
            . 'File: ' . $e->getFile() . PHP_EOL
            . 'Message: '. $e->getMessage() . PHP_EOL . PHP_EOL
            . 'Trace: ' . $e->getTraceAsString();

        while ($e = $e->getPrevious()) {
            $emailBody .= PHP_EOL . PHP_EOL. 'Previous Exception: ' . PHP_EOL;
            $emailBody .= 'Line: ' . $e->getLine() . PHP_EOL
                . 'File: ' . $e->getFile() . PHP_EOL
                . 'Message: '. $e->getMessage() . PHP_EOL . PHP_EOL
                . 'Trace: ' . $e->getTraceAsString();
        }

        $mail = new Mail\Message();
        $mail->setBody($emailBody);
        $mail->addTo(self::$sendTo);
        $mail->setSubject('Error Handler');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
}