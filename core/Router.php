<?php
namespace core;
use start\Config;
use middlewares\Authontication as Authontication;

class Router {
    private $routes = [];
    private $requestMethods = array('GET', 'POST', 'PUT', 'DELETE');

    public function get($pattern, $handler, $middleware = [])
    {
        $this->routes['GET'][$pattern]['path'] = $handler;
        if($middleware){
            $this->routes['GET'][$pattern]['middleware'] = $middleware;
        }
    }

    public function post($pattern, $handler, $middleware = [])
    {
        $this->routes['POST'][$pattern]['path'] = $handler;
        if(count($middleware) > 0){
            $this->routes['POST'][$pattern]['middleware'] = $middleware;
        }
    }

    public function put($pattern, $handler, $middleware = [])
    {
        $this->routes['PUT'][$pattern]['path'] = $handler;
        if($middleware){
            $this->routes['PUT'][$pattern]['middleware'] = $middleware;
        }
    }

    public function delete($pattern, $handler, $middleware = [])
    {
        $this->routes["DELETE"][$pattern]['path'] = $handler;
        if($middleware){
            $this->routes['DELETE'][$pattern]['middleware'] = $middleware;
        }
    }

    public function any($pattern, $handler, $middleware = [])
    {
        foreach($this->requestMethods as $method){
            $this->routes[$method][$pattern]['path'] = $handler;
            if($middleware){
                $this->routes[$method][$pattern]['middleware'] = $middleware;
            }
        }
    }

    public function handleRequest($method, $url)
    {
        if(isset($this->routes[$method])){
            foreach ($this->routes[$method] as $pattern => $data) {
                $handler = $data['path'];
                $regexPattern = preg_replace('/\//', '\\/', $pattern);
                $regexPattern = preg_replace('/\:\w+/', '([^\/]+)', $regexPattern);
                $regexPattern = '/^' . $regexPattern . '\/?$/'; // Sonundaki / işaretini opsiyonel hale getir
    
                if (preg_match($regexPattern, $url, $matches)) {
                    array_shift($matches);

                    /* middleware control */
                    if(isset($this->routes[$method][$pattern]['middleware'])){
                        $middlewareName = $this->routes[$method][$pattern]['middleware'][0];
                        if(in_array($middlewareName, array_keys(Config::$middlewares))
                            || file_exists('middlewares/'. $middlewareName.'.php')) {

                            if(in_array($middlewareName, array_keys(Config::$middlewares))){
                                $class = "middlewares\\".Config::$middlewares[$middlewareName];
                            }else{
                                $class = "middlewares\\".$middlewareName;
                            }

                            new $class();
                        }else{
                            Response::json(['status' => 'error', 'message' => 'middleware not found'], 404);
                        }
                    }
                    call_user_func_array($handler, $matches);
                    return;
                }
            }
        }

        Response::json(['status' => 'error', 'message' => 'incorrect path or request method'], 404);
        die;
    }
}