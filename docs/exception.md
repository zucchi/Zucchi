# Exceptions

## ErrorException

This is a clean wrapper around the SPL_ErrorException that is utilised by the Error Handler

## ErrorHandler

This is a simple class that provides an Errorhander for errors that will convert them into Exceptions. It also provides
an implementation of a simple mail function to send error reports to a specified email address;

To set the email address to send to you need to use

```
\Zucchi\Exception\ErrorHandler::sendTo = 'dev@example.com';
```