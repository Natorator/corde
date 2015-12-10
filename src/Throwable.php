<?php

namespace Abimo;

class Throwable
{
    /**
     * An instance of config class.
     *
     * @var \Abimo\Config
     */
    public $config;

    /**
     * The error messages array .
     *
     * @var array
     */
    public $errorMessages = array(
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED'
    );

    /**
     * The exception messages array .
     *
     * @var array
     */
    public $exceptionMessages = array(
        //Predefined Exceptions
        89 => 'Exception',
        90 => 'ErrorException',
        91 => 'Error',
        92 => 'ArithmeticError',
        93 => 'AssertionError',
        94 => 'DivisionByZeroError',
        95 => 'ParseError',
        96 => 'TypeError',
        //SPL Exceptions
        97 => 'BadFunctionCallException',
        98 => 'BadMethodCallException',
        99 => 'DomainException',
        100 => 'InvalidArgumentException',
        101 => 'LengthException',
        102 => 'LogicException',
        103 => 'OutOfBoundsException',
        104 => 'OutOfRangeException',
        105 => 'OverflowException',
        106 => 'RangeException',
        107 => 'RuntimeException',
        108 => 'UnderflowException',
        109 => 'UnexpectedValueException'
    );

    /**
     * An instance of router class.
     *
     * @var \Abimo\Router
     */
    public $router;

    /**
     * An instance of template class.
     *
     * @var \Abimo\Template
     */
    public $template;

    /**
     * An array that holds throwable data.
     *
     * @var array
     */
    public $throwable = array();

    /**
     * Create a new throwable instance.
     *
     * @param \Abimo\Config   $config
     * @param \Abimo\Router   $router
     * @param \Abimo\Template $template
     */
    public function __construct(Config $config, Router $router, Template $template)
    {
        $this->config['app'] = $config->app;
        $this->config['throwable'] = $config->throwable;
        $this->router = $router;
        $this->template = $template;
    }

    /**
     * Set throwable handlers.
     *
     * @return void
     */
    public function register()
    {
        ini_set('display_errors', $this->config['throwable']['display']);
        ini_set('reporting', $this->config['throwable']['reporting']);
        ini_set('log_errors', $this->config['throwable']['log']);
        ini_set('error_log', $this->config['throwable']['path']);

        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    /**
     * Make a new throwable.
     *
     * @param int    $code
     * @param string $type
     * @param string $message
     * @param string $file
     * @param mixed  $line
     *
     * @return void
     */
    public function make($code, $type, $message, $file, $line = '')
    {
        $this->throwable = array('code' => $code, 'type' => $type, 'message' => $message, 'file' => $file, 'line' => $line);
    }

    /**
     * Register error handler.
     *
     * @param int    $code
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function errorHandler($code, $message, $file, $line)
    {
        $this->make($code, $this->getErrorMessage($code), $message, $file, $line);

        exit;
    }

    /**
     * Get error message.
     *
     * @param int $code
     *
     * @return string
     */
    public function getErrorMessage($code)
    {
        $default = 1;

        return isset($this->errorMessages[$code]) ? $this->errorMessages[$code] : $this->errorMessages[$default];
    }

    /**
     * Register exception handler.
     *
     * @param \Exception $exception
     */
    public function exceptionHandler(\Exception $exception)
    {
        $this->make($exception->getCode(), $this->getExceptionMessage($exception->getCode()), $exception->getMessage(), $exception->getFile(), $exception->getLine());

        exit;
    }

    /**
     * Get exception message.
     *
     * @param int $code
     *
     * @return string
     */
    public function getExceptionMessage($code)
    {
        $default = 89;

        return isset($this->exceptionMessages[$code]) ? $this->exceptionMessages[$code] : $this->exceptionMessages[$default];
    }

    /**
     * Register shutdown handler.
     */
    public function shutdownHandler()
    {
        if ($throwable = error_get_last()) {
            $this->make($throwable['type'], $throwable['message'], $throwable['file'], $throwable['line']);
        }

        if (!empty($this->throwable)) {
            ob_get_clean();

            if (empty($this->config['app']['development'])) {
                $this->router->action = implode('.', array($this->config['throwable']['controller'], $this->config['throwable']['action']));
                echo $this->router->run();
            } else {
                $style = $this->template
                    ->file(__DIR__.DIRECTORY_SEPARATOR.'Throwable'.DIRECTORY_SEPARATOR.'Style.css')
                    ->render();

                echo $this->template
                    ->file(__DIR__.DIRECTORY_SEPARATOR.'Throwable'.DIRECTORY_SEPARATOR.'Dashboard.php')
                    ->set('style', $style)
                    ->set('throwable', $this->throwable)
                    ->render();
            }
        }
    }
}
