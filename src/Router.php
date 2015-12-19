<?php

namespace Abimo;

class Router
{
    /**
     * @var mixed
     */
    public $action;

    /**
     * @var array
     */
    public $args = [];

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    public $map = [];

    /**
     * @var array
     */
    public $patterns = [
        ':number' => '?([0-9]+)',
        ':slug' => '?([^/]+)'
    ];

    /**
     * @var Request
     */
    private $request;

    /**
     * An array of routes.
     *
     * @var array
     */
    public $routes = [];

    /**
     * Router constructor.
     *
     * @param Config $config
     * @param Request $request
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

        if (array_key_exists($method = $this->request->method(), $this->routes)) {
            $routes = $this->routes[$method];
        }

        if (array_key_exists('all', $this->routes)) {
            $routes = $routes + $this->routes['all'];
        }

        return $routes;
    }

    /**
     * Get the url by route.
     *
     * @param string $route
     * @param array $args
     *
     * @return mixed
     */
    public function url($route, $args = [])
    {
        if (empty($this->map[$route])) {
            return null;
        }

        if (!is_array($args)) {
            $args = [$args];
        }

        $exploded = explode('/', $this->map[$route]);

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

        $pattern = str_replace($aliases, $patterns, $this->map[$route]);

        if (preg_match('~^'.$pattern.'$~', $url = implode('/', $url))) {
            return $url;
        }

        return null;
    }

    /**
     * Match the request uri against the routes.
     *
     * @return Router
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
     * Register the route.
     *
     * @param string $method
     * @param string $pattern
     * @param mixed $routes
     * @param callable $action
     *
     * @return void
     */
    public function register($method, $pattern, $routes, $action)
    {
        if (is_array($routes)) {
            foreach ($routes as $route) {
                $this->map[$route] = $pattern;
            }
        } else {
            $this->map[$routes] = $pattern;
        }

        $this->routes[$method][$pattern] = $action;
    }

    /**
     * Run the router.
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
     * Magically register the new route.
     *
     * @param string $method
     * @param array $args
     *
     * @return void
     */
    public function __call($method, $args)
    {
        $this->register($method, array_shift($args), array_shift($args), array_shift($args));
    }
}
