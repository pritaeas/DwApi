<?php
class DwApiException extends Exception
{
    const UNKNOWN_EXCEPTION = 'Unknown Exception';

    protected static $exceptions = array
    (
        4001 => 'Access token required'
    );

    public static function Create($code = 0)
    {
        $message = in_array($code, array_keys(self::$exceptions)) ? self::$exceptions[$code] : UNKNOWN_EXCEPTION;
        return new DwApiException($message, $code, null);
    }
}
?>