<?php
namespace SavantPHP;

/**
 * Class SavantPHPerror
 * @package SavantPHP
 */
class SavantPHPerror
{
    /**
     * The error code, typically a SavantPHP 'ERR_*' string.
     *
     * @var string
     */
    public $code = null;

    /**
     * An array of error-specific information.
     *
     * @var array
     */
    public $info = [];

    /**
     * The error severity level.
     *
     * @var int
     */
    public $level = E_USER_ERROR;

    /**
     * A debug backtrace for the error, if any.
     *
     * @var array
     */
    public $trace = null;


    /**
     * Constructor.
     *
     * @param array $conf An associative array where the KEY is a SavantPHPerror property and the VALUE is the value for that property.
     */
    public function __construct($conf = [])
    {
        // set public properties
        foreach ($conf as $key => $val) {
            $this->$key = $val;
        }
        // add a backtrace
        if ($conf['trace'] === true) {
            $this->trace = debug_backtrace();
        }
    }

    /**
     * Magic method for output dump.
     *
     * @return String
     */
    public function __toString()
    {
        ob_start();
        echo get_class($this) . ': ';
        print_r(get_object_vars($this));
        $content = ob_get_clean();

        return $content;
    }
}