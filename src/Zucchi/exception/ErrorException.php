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
 * ErrorException - Class Description
 *
 * @author David Seward <dave@zucchi.co.uk>
 * @package Zucchi
 * @subpackage Exception
 * @category Components
 */
class ErrorException extends \Exception/*\RuntimeException*/
{
    protected   $errNo;

    public function __construct($msg)
    {
        parent::__construct($msg);
        $this->errNo = 0;
    }

    public function setNo($no)
    {
       $this->errNo = $no;
    }

    public function setFile($errfile)
    {
        $this->file = $errfile;
    }

    public function setLine($errline)
    {
        $this->line = $errline;
    }

    public function getNo()
    {
        return $this->errNo;
    }

    /*Sends email error report
    *
    */
    static public function sendErrorReport($error)
    {
        echo 'sending mail';
        $emailBody = 'Error Report' . PHP_EOL;

        $emailBody = $emailBody
            . 'Exception Type: ' . $error->exceptionType . PHP_EOL
            . 'Error No: '. (string) $error->errorNo . PHP_EOL
            . 'Line: ' . $error->errorLine . PHP_EOL
            . 'File: ' . $error->errorFile . PHP_EOL
            . 'Message: '. $error->message . PHP_EOL
            . PHP_EOL;


        $mail = new Message();
        $mail->setBody($emailBody);
        $mail->addFrom('utagserver@zucchi.co.uk');
        $mail->addTo('dev@zucchi.co.uk');
        $mail->setSubject('Tag Error');

        // $transport = new Mail\Transport\Sendmail();

        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
            'name'              => 'localhost.localdomain',
            'host'              => '127.0.0.1',
            'port'              => 25,
        ));
        $transport->setOptions($options);
        $transport->send($mail);

    }

}