<?php

namespace App\Routing;

class Router {

    /**
     * Array that holds all routes
     *
     * @var array
     */
    private $routes = [];

    /**
     * Path to controllers
     *
     * @var string
     */
    private $controllersPath = 'App\Controllers\\';

    /**
     * Adds route to array
     *
     * @param string $method request method
     * @param string $url
     * @param string $controller
     * @param string $action controller method
     * @return Router
     */
    public function addRoute(string $method, string $url, string $controller, string $action) 
    {
        $method = strtoupper($method);

        $url = rtrim($url, '/');

        $controller = $this->controllersPath . $controller;

        $type = $this->checkType($url);

        $this->routes[$type][$method][$url] = [
            'controller' => $controller,
            'action' => $action
        ];

        return $this;
    }

    /**
     * Checks if route is static or dynamic
     *
     * @param string $url
     * @return string
     */
    private function checkType(string $url)
    {
        return strpos($url, ':') ? 'dynamic' : 'static';
    }

    /**
     * Same as addRoute only for GET
     *
     * @param string $url
     * @param string $controller
     * @param string $action
     * @return Router
     */
    public function get(string $url, string $controller, string $action) 
    {
        $this->addRoute('GET', $url, $controller, $action);

        return $this;
    }
    
    /**
     * Main method
     *
     * @param string $method
     * @param string $url
     * @return mixed response
     */
    public function run(string $method, string $url) 
    {
        $url = rtrim($url, '/');

        // check if route is in statics
        $route = $this->routes['static'][$method][$url] ?? null;

        // if not check dynamics
        if (!$route)
        {
            $route = $this->searchInDynamic($method, $url); 
        }

        // 404 not found
        if (!$route)
        {
            return $this->handleNotFound();
        }
                
        $controller = new $route['controller'];
        
        $action = $route['action'];

        $args = $route['args'] ?? [];
        
        return $controller->$action(...$args);
    }

    /**
     * Checks if url is dynamic
     *
     * @param string $method
     * @param string $url
     * @return array|null 
     */
    private function searchInDynamic(string $method, string $url) 
    {
        // check if request method exists in array
        if (!isset($this->routes['dynamic'][$method])) 
        {
            return null;
        }

        // split url for easier comparison
        $url = explode('/', $url);

        // we look for route only under given method
        foreach($this->routes['dynamic'][$method] as $route => $action)
        {
            // split route for easier comparison 
            $route = explode('/', $route);

            // get placeholder elements for the route
            $placeholders = preg_grep('/:.+/', $route);
            
            // get difference between url and route 
            $params = array_diff($url, $route);
            
            // if only difference is in placeholder places
            // we got the match
            if(array_keys($placeholders) === array_keys($params)) 
            {
                // add difference as params to pass to controller
                $action['args'] = $params;
                return $action;
            }
        }

        return null;
    }

    /**
     * Simple 404
     *
     * @return void
     */
    public function handleNotFound() 
    {
        http_response_code(404);
        exit();
    }
}