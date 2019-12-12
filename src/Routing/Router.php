<?php

namespace App\Routing;

class Router {

    private $routes = [];
    private $controllersPath = 'App\Controllers\\';

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

    private function checkType(string $url)
    {
        return strpos($url, ':') ? 'dynamic' : 'static';
    }

    public function get(string $url, string $controller, string $action) 
    {
        $this->addRoute('GET', $url, $controller, $action);
        return $this;
    }
    
    public function run(string $method, string $url) 
    {
        $url = rtrim($url, '/');

        $route = $this->routes['static'][$method][$url] ?? null;

        if (!$route)
        {
            $route = $this->searchInDynamic($method, $url); 
        }

        if (!$route)
        {
            return $this->handleNotFound();
        }
                
        $controller = new $route['controller'];
        
        $action = $route['action'];

        $args = $route['args'] ?? [];
        
        return $controller->$action(...$args);
    }

    private function searchInDynamic(string $method, string $url) 
    {
        if (!isset($this->routes['dynamic'][$method])) 
        {
            return null;
        }

        $url = explode('/', $url);

        foreach($this->routes['dynamic'][$method] as $route => $action)
        { 
            $route = explode('/', $route);

            $placeholders = preg_grep('/:.+/', $route);
            
            $params = array_diff($url, $route);
            
            if(array_keys($placeholders) === array_keys($params)) 
            {
                $action['args'] = $params;
                return $action;
            }
        }

        return null;
    }


    public function handleNotFound() 
    {
        http_response_code(404);
        exit();
    }
}