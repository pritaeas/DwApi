<?php
class DwApiException extends Exception
{
    /**
     * Default exception message for unrecognized codes.
     */
    const EX_UNKNOWN = 'Unknown Exception';

    /**
     * Configuration exceptions.
     */
    const EX_CONFIGURATION = 1001;

    /**
     * Invalid parameter exceptions.
     */
    const EX_ACCESS_TOKEN = 2001;

    const EX_INVALID_ARRAY = 2102;
    const EX_INVALID_BOOL = 2103;
    const EX_INVALID_INT = 2104;
    const EX_INVALID_STRING = 2105;

    const EX_INVALID_TYPE_ARTICLE = 2201;
    const EX_INVALID_TYPE_POST = 2202;
    const EX_INVALID_TYPE_RELATION = 2203;
    const EX_INVALID_TYPE_RSS_ARTICLE = 2204;

    const EX_INVALID_TYPE_MAIL_BOX = 2301;
    const EX_INVALID_TYPE_VOTE = 2302;
    const EX_INVALID_TYPE_WATCH = 2303;

    /**
     * Communication exceptions.
     */
    const EX_CURL = 3001;
    const EX_FOPEN = 3002;

    /**
     * DaniWeb exceptions.
     */
    const EX_DANIWEB = 4001;

    /**
     * @var array List of exception messages.
     */
    protected $exceptionMessages = array
    (
        self::EX_CONFIGURATION => 'curl and allow_url_fopen disabled',

        self::EX_ACCESS_TOKEN => 'Access token required',
        self::EX_INVALID_ARRAY => 'Invalid parameter, array expected',
        self::EX_INVALID_BOOL => 'Invalid parameter, boolean expected',
        self::EX_INVALID_INT => 'Invalid parameter, positive integer expected',
        self::EX_INVALID_STRING => 'Invalid parameter, non-empty string expected',

        self::EX_INVALID_TYPE_ARTICLE => 'Invalid parameter, article type expected',
        self::EX_INVALID_TYPE_POST => 'Invalid parameter, post type expected',
        self::EX_INVALID_TYPE_RELATION => 'Invalid parameter, relation type expected',
        self::EX_INVALID_TYPE_RSS_ARTICLE => 'Invalid parameter, RSS article type expected',

        self::EX_INVALID_TYPE_MAIL_BOX => 'Invalid parameter, mail box type expected',
        self::EX_INVALID_TYPE_VOTE => 'Invalid parameter, vote type expected',
        self::EX_INVALID_TYPE_WATCH => 'Invalid parameter, watch type expected',

        self::EX_CURL => 'curl failed',
        self::EX_FOPEN => 'file_get_contents failed',

        self::EX_DANIWEB => 'Invalid request or no data'
    );

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $message =
            (in_array($code, array_keys($this->exceptionMessages)) ? $this->exceptionMessages[$code] : self::EX_UNKNOWN) .
            ((is_string($message) and !empty($message)) ? ' -- ' . $message : '');

        parent::__construct($message, $code, $previous);
    }
}
?>