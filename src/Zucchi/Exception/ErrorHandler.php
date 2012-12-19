<?php
/**
 * Zucchi (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Exception;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

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
     * Sends email error report
     * @param
     */
    static public function sendErrorReport(\Exception $e)
    {
        $emailBody = 'Error Report' . PHP_EOL;

        $emailBody = $emailBody
            . 'Exception Type: ' . get_class($e) . PHP_EOL
            . 'Error No: '. $e->getCode(). PHP_EOL
            . 'Line: ' . $e->getLine() . PHP_EOL
            . 'File: ' . $e->getFile() . PHP_EOL
            . 'Message: '. $e->getMessage() . PHP_EOL
            . PHP_EOL;


        $mail = new Message();
        $mail->setBody($emailBody);
        $mail->addTo('dev@zucchi.co.uk');
        $mail->setSubject('Tag Error');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);

    }
}