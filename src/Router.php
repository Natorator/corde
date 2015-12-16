<?php

namespace Abimo;

class Router
{
    /**
     * An action to execute.
     *
     * @var mixed
     */
    public $action;

    /**
     * An array of args.
     *
     * @var array
     */
    public $args = [];

    /**
     * An instance of config class.
     *
     * @var callable
     */
    public $config;

    /**
     * An array of route-pattern mapping.
     *
     * @var array
     */
    public static $map;

    /**
     * An array of route patterns.
     *
     * @var array
     */
    public $patterns = [
        ':number' => '?([0-9]+)',
        ':slug' => '?([^/]+)'
    ];

    /**
     * An instance of request class.
     *
     * @var callable
     */
    public $request;

    /**
     * An array of routes.
     *
     * @var array
     */
    public static $routes = [];

    /**
     * Create a new response instance.
     * @param \Abimo\Config $config
     * @param \Abimo\Request $request
     */
    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Get all routes.
     *
     * @return array
     */
    private function routes()
    {
        $routes = [];

        require APP_PATH.DIRECTORY_SEPARATOR.'Misc'.DIRECTORY_SEPARATOR.'Routes.php';

        if (array_key_exists($method = $this->request->method(), static::$routes)) {
            $routes = static::$routes[$method];
        }

        if (array_key_exists('all', static::$routes)) {
            $routes = $routes + static::$routes['all'];
        }

        return $routes;
    }

    /**
     * Get an url by route.
     *
     * @param string $route
     * @param array  $args
     *
     * @return mixed
     */
    public function url($route, $args = [])
    {
        if (empty(static::$map[$route])) {
            return null;
        }

        if (!is_array($args)) {
            $args = [$args];
        }

        $exploded = explode('/', static::$map[$route]);

        $url = [];

        foreach ($exploded as $exp) {
            if (false !== strpos($exp, ':')) {

                $arg = array_shift($args);

                if (null === $arg && false === strpos($exp, '?')) {
                    return null;
                } elseif (null !== $arg) {
                    $url[] = $arg;
                }

                continue;
            }

            $url[] = $exp;
        }

        $aliases = array_keys($this->patterns);
        $patterns = array_values($this->patterns);

        $pattern = str_replace($aliases, $patterns, static::$map[$route]);

        if (preg_match('~^'.$pattern.'$~', $url = implode('/', $url))) {
            return $url;
        }

        return null;
    }

    /**
     * Match request uri against routes.
     *
     * @return \Abimo\Router
     *
     * @throws \ErrorException
     */
    public function match()
    {
        $method = $this->request->method();
        $uri = $this->request->uri();

        $routes = $this->routes();
        $aliases = array_keys($this->patterns);
        $patterns = array_values($this->patterns);

        foreach ($routes as $pattern => $action) {
            if (false !== strpos($pattern, ':')) {
                $pattern = str_replace($aliases, $patterns, $pattern);
            }
            if (preg_match('~^' . $pattern . '$~', $uri, $match)) {
                $this->action = $action;
                $this->args = str_replace('/', '', array_slice($match, 1));

                return $this;
            }
        }

        throw new \ErrorException("Route $method $uri not found.", 90);
    }

    /**
     * Register a new route.
     *
     * @param string   $method
     * @param string   $pattern
     * @param mixed    $routes
     * @param callable $action
     *
     * @return void
     */
    public static function register($method, $pattern, $routes, $action)
    {
        if (is_array($routes)) {
            foreach ($routes as $route) {
                static::$map[$route] = $pattern;
            }
        } else {
            static::$map[$routes] = $pattern;
        }

        static::$routes[$method][$pattern] = $action;
    }

    /**
     * Run router in respect to action and args.
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \ErrorException
     */
    public function run()
    {
        if (empty($this->action)) {
            throw new \InvalidArgumentException("Action must not be empty.", 100);
        }

        if ($this->action instanceof \Closure) {
            return call_user_func_array($this->action, $this->args);
        }

        $actions = explode('.', $this->action);

        $class = '\App\Controllers\\'.array_shift($actions);
        $method = array_shift($actions);

        if (method_exists($class, $method)) {
            return call_user_func_array([new $class, $method], $this->args);
        }

        throw new \ErrorException("Action $this->action doesn't exist.", 100);
    }

    /**
     * Magically register a new route.
     *
     * @param string $method
     * @param array  $args
     *
     * @return void
     */
    public static function __callStatic($method, $args)
    {
        static::register($method, array_shift($args), array_shift($args), array_shift($args));
    }
}
