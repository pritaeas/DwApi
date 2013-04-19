<?php
class DwApiException extends Exception
{
    const UNKNOWN_EXCEPTION = 'Unknown Exception';

    protected $exceptions = array
    (
        2011 => 'Invalid forum ID',
        2021 => 'Invalid RSS article type',
        4001 => 'Access token required',
        4011 => 'Invalid article ID',
        4012 => 'Invalid post ID',
        4021 => 'Invalid mail box type',
        4022 => 'Invalid vote type',
        4023 => 'Invalid watch type'
    );

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $message = in_array($code, array_keys($this->exceptions)) ? $this->exceptions[$code] : self::UNKNOWN_EXCEPTION;
        parent::__construct($message, $code, $previous);
    }
}
?>