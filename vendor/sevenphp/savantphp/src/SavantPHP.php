<?php
namespace SavantPHP;
/**
 * SavantPHP provides an object-oriented, yet minimalistic, templating system approach for PHP.
 * It helps you separate business logic from presentation logic using PHP as the template language.
 * SavantPHP does not compile templates.
 *
 * NOTE:
 *      1) This is a rewrite / revamp from the old 'PHP Savant' by Paul M. Jones, which was then handedover to Brett Bieber (have a look/compare here <https://github.com/saltybeagle/Savant3>)
 *      2) This project is named as "SavantPHP" - please do not confuse with the other "PHP Savant"
 *      3) This new "SavantPHP" has been revamped & is maintained by Wasseem Khayrattee <savantphp@7php.com> | A die-hard fan of the idea of Savant by The Mighty Paul M. Jones who is now the author of AuraPHP
 *
 * Class Savant
 * @package SavantPHP
 * @license https://github.com/7php/SavantPHP/blob/master/LICENSE.md (MIT)license
 * @link    http://7php.github.io/SavantPHP/
 */

class SavantPHP
{
    const TPL_PATH_LIST     = 'template_path_list';
    const TPL_FILE          = 'template';
    const FETCHED_TPL_FILE  = 'fetched_tpl';
    const ERROR_TEXT        = 'error_text';
    const EXCEPTIONS        = 'exceptions';
    const CONTAINER         = 'container';
    const CONFIG_LIST       = 'configList';

    /**
     * Array of configuration parameters.
     *
     * @access protected
     * @var array
     */
    protected $configList = [
        self::TPL_PATH_LIST     => [],
        self::TPL_FILE          => null,
        self::CONTAINER         => null,
        self::ERROR_TEXT        => "\n\ntemplate error, examine fetch() result\n\n",
        self::EXCEPTIONS        => true, //Let's throw exception by default
        self::FETCHED_TPL_FILE  => null, //NOTE: As a user, do not set this - when error you can examine this as it will contained the fetched template file
    ];

    /*
     * this can be any container you want to use, e.g Pimple or even League/Container
     * it's here so that inside your TPL you can directly access your container
     */
    protected $container;


    /**
     * SavantPHP constructor.
     *
     * @param null $config  - An associative array of configuration keys for the SavantPHP object.  Any, or none, of the keys may be set.
     */
    public function __construct($config = null)
    {
        // force the config to an array
        settype($config, 'array');

        // set the default template search path
        if (isset($config[self::TPL_PATH_LIST])) {
            $this->setPath($config[self::TPL_PATH_LIST]); // path to your theme's (or VIEW) directory
        } else {
            $this->setPath(null);
        }

        // set the template to use for output aka TPL files (.tpl.php) although there's no enforcement in having the .tpl.php extension explicitely
        if (isset($config[self::TPL_FILE])) {
            $this->setTemplate($config[self::TPL_FILE]);
        }

        // if the user is using any kind of DI Container, let's inject it inside SavantPHP for him/her
        if (isset($config[self::CONTAINER]) && is_object($config[self::CONTAINER])) {
            $this->setContainer($config[self::CONTAINER]);
        }

        // set the error reporting text
        if (isset($config[self::ERROR_TEXT])) {
            $this->setErrorText($config[self::ERROR_TEXT]);
        }

        // set the exceptions flag | Booleab
        if (isset($config[self::EXCEPTIONS])) {
            $this->setExceptions($config[self::EXCEPTIONS]);
        }
    }

    /**
     * Magic method to echo this object as a string.
     *
     * In case of error, this will output a that error_text string and will not return an error object.
     * So in that case, use fetch() to get an error object when errors occur if you want to inspect deeper.
     *
     * @return string The template output.
     */
    public function __toString()
    {
        return $this->getOutput();
    }

    /**
     * Returns the SavantPHP configuration parameter bag.
     *
     * @param string $key The specific configuration key to return.  If null, returns the entire configuration array.
     * @return mixed
     */
    public function getConfigList($key = null)
    {
        if (is_null($key)) {
            return $this->configList; // no key requested, return the entire configuration bag
        } elseif (empty($this->configList[$key])) {
            return null; // no such key
        } else {
            return $this->configList[$key]; // return the requested key
        }
    }

    /**
     * Returns the container object
     * NOTE:
     *      1) If you want to access ONLY the container => use $this->container directly instead of this method - more shortand/handy inside TPL than writing $this->getContainer()
     *      2) inside a TPL, you can use directly $this->container['key'] instead of $this->getFromContainer('key') - more short/handy
     *
     * @param null $key
     * @return null
     */
    public function getContainer($key = null)
    {
        if (is_null($key)) { // no key requested, attempt to return the entire container bag
            if (!is_object($this->container)) {
                return null;
            }
            return $this->container;

        } elseif (empty($this->container[$key])) { //if KEY does not exist
            return null;
        }
        //OK, let's get that "thing" by KEY now
        return $this->container[$key];
    }

    /**
     * If the user is using a container, allow injecting it in SavantPHP here.
     * Setter method in case not injected via constructor or needs an override
     *
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Sets the custom error text for __toString().
     * Setter method in case not injected via constructor or needs an override
     *
     * @param string $text The error text when a template is echoed.
     * @return void
     */
    public function setErrorText($text)
    {
        $this->configList[self::ERROR_TEXT] = $text;
    }

    /**
     * Sets whether or not exceptions will be thrown.
     * Setter method in case not injected via constructor or needs an override
     *
     * @param bool $flag True to turn on exception throwing ON, false to turn it OFF.
     * @return void
     */
    public function setExceptions($flag)
    {
        $this->configList[self::EXCEPTIONS] = (bool) $flag;
    }

    /**
     * Sets the template name to use.
     *
     * @param string $template The template name.
     * @return void
     */
    public function setTemplate($template)
    {
        $this->configList[self::TPL_FILE] = $template;
    }

    /**
     * Sets an entire array of possible search paths for templates
     *
     * @param string|array $path
     */
    public function setPath($path)
    {
        // clear out the prior search dirs
        $this->configList[self::TPL_PATH_LIST] = [];
        // always add the fallback directories as last resort
        $this->addPath('.');
        // actually add the user-specified directory
        $this->addPath($path);
    }

    /**
     * Adds to the search path for templates and resources.
     *
     * @param string|array $path The directory or stream to search.
     * @return void
     */
    public function addPath($path)
    {
        // convert from path string to array of directories
        if (is_string($path) && ! strpos($path, '://')) {
            // the path config is a string, and it's not a stream
            // identifier (the "://" piece). add it as a path string.
            $path = explode(PATH_SEPARATOR, $path);

            // typically in path strings, the first one is expected
            // to be searched first. however, SavantPHP uses a stack,
            // so the first would be last.  reverse the path string
            // so that it behaves as expected with path strings.
            $path = array_reverse($path);
        } else {
            // just force to array
            settype($path, 'array');
        }

        // loop through the path directories
        foreach ($path as $dir) {
            // no surrounding spaces allowed!
            $dir = trim($dir);
            // add trailing separators as needed
            if (strpos($dir, '://') && substr($dir, -1) != '/') {
                // stream
                $dir .= '/';
            } elseif (substr($dir, -1) != DIRECTORY_SEPARATOR) {
                // directory
                $dir .= DIRECTORY_SEPARATOR;
            }
            // add to the top of the search dirs
            array_unshift(
                $this->configList[self::TPL_PATH_LIST],
                $dir
            );
        }
    }

    /**
     * Searches the directory paths for a given file.
     *
     * @param string $file The file name to look for.
     * @return string|bool The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
     */
    protected function findFile($file)
    {
        $pathList = $this->configList[self::TPL_PATH_LIST];
        foreach ($pathList as $path) {
            // get the path to the file
            $fullPathToFile = $path . $file;
            // is the path based on a stream?
            if (strpos($path, '://') === false) {
                /* not a stream, so do a realpath() to avoid directory traversal attempts on the local file system. [Suggested by Ian Eure] */
                $path = realpath($path); // needed for substr() later
                $fullPathToFile = realpath($fullPathToFile);
            }

            /* the substr() check added by [Ian Eure] to make sure that the realpath() results in a directory registered
               with SavantPHP so that non-registered directores are not accessible via directory traversal attempts. */
            if (file_exists($fullPathToFile) && is_readable($fullPathToFile) &&
                substr($fullPathToFile, 0, strlen($path)) == $path) {
                return $fullPathToFile;
            }
        }
        // could not find the file in the set of paths
        return false;
    }

    /**
     * Displays a template directly (equivalent to <code>echo $tpl</code>).
     *
     * @param null $tpl
     */
    public function display($tpl = null)
    {
        echo $this->getOutput($tpl);
    }

    /**
     * Returns output, including self::ERROR_TEXT if an error occurs.
     *
     * @param null $tpl
     * @return mixed
     */
    public function getOutput($tpl = null)
    {
        $output = $this->fetch($tpl);
        if ($this->isError($output)) {
            $text = $this->configList[self::ERROR_TEXT];
            return $text; //TODO: escape this output?
        } else {
            return $output;
        }
    }

    /**
     * Gets & executes a template source.
     *
     * @access public
     * @param string $tpl The template to process; if null, uses the
     * default template set with setTemplate().
     * @return mixed The template output string, or a SavantPHPerror.
     */
    public function fetch($tpl = null)
    {
        // make sure we have a template source to work with
        if (is_null($tpl)) {
            $tpl = $this->configList[self::TPL_FILE];
        }
        $result = $this->getPathToTemplate($tpl);

        if (! $result || $this->isError($result)) { //if no path
            return $result;
        } else {
            $this->configList[self::FETCHED_TPL_FILE] = $result;
            unset($result);
            unset($tpl);

            // buffer output so we can return it instead of displaying.
            ob_start();

            include $this->configList[self::FETCHED_TPL_FILE];
            $this->configList[self::FETCHED_TPL_FILE] = null; //flush fetched script value

            return ob_get_clean();
        }
    }

    /**
     * returns path to the source of template file
     * Usage Example:
     *      1) inside class here, $this->getPathToTemplate($tpl); //it will return the full path only
     *
     *      2) (inside a template script): BUT use the method includeTemplate() below
     *          <code>
     *              $this->getPathToTemplate($tpl, true); //if TRUE, it acts as include $tpl hence returning the whole CONTENT of the tpl
     *          </code>
     *
     *
     * @param null $tpl
     * @param bool $include
     * @return bool|SavantPHPerror|string
     * @throws SavantPHPexception
     */
    protected function getPathToTemplate($tpl = null, $include = false)
    {
        // set to default template if none specified.
        if (is_null($tpl)) {
            $tpl = $this->configList[self::TPL_FILE];
        }
        // find the template source.
        $file = $this->findFile($tpl);
        if (! $file) {
            return $this->error(
                'ERR_TEMPLATE',
                [self::TPL_FILE => $tpl]
            );
        }
        if ($include) {
            include $file;
        } else {
            return $file;
        }
    }

    /**
     * will include a template - to be used inside a TPL file
     * Usage example:
     *      (inside a template script): BUT use the method includeTemplate() below
     *          <code>
     *              $this->includeTemplate($tpl); //it acts as include $tpl hence returning the whole CONTENT of the tpl
     *          </code>
     *
     * @param $tpl
     */
    protected function includeTemplate($tpl)
    {
        $this->getPathToTemplate($tpl, true);
    }

    /**
     * Returns an error object or throws an exception.
     *
     * @param $code
     * @param array $info
     * @param int $level
     * @param bool $trace
     * @return SavantPHPerror
     * @throws SavantPHPexception
     */
    public function error($code, $info = [], $level = E_USER_ERROR, $trace = true)
    {
        if ($this->configList[self::EXCEPTIONS]) {
            throw new SavantPHPexception($code);
        }
        // the error config array
        $config = [
            'code'  => $code,
            'info'  => (array) $info,
            'level' => $level,
            'trace' => $trace
        ];
        return new SavantPHPerror($config);
    }

    /**
     * Tests if an object is of the SavantPHPerror class.
     *
     * @param $obj
     * @return bool
     */
    public function isError($obj)
    {
        // is it even an object?
        if (! is_object($obj)) { // if not an object, can't even be a SavantPHPerror object
            return false;
        } else {
            // now compare the parentage
            $is = $obj instanceof SavantPHPerror;
            $sub = is_subclass_of($obj, 'SavantPHPerror');
            return ($is || $sub);
        }
    }
}