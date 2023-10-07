<?php

namespace System;

use App\Responses\Response;
use JetBrains\PhpStorm\ArrayShape;

class Router
{
    public static function contentToRender(): void
    {
        $uri = self::processURI();
        if (class_exists($uri['controller'])) {
            $controller = $uri['controller'];
            $method = $uri['method'];
            $args = $uri['args'];
            $requestClass = self::recognizeRequestInControllerMethod($controller, $method);
            try {
                $args ? $controller::{$method}(new $requestClass, ...$args) :
                    $controller::{$method}(new $requestClass);
            } catch (\Exception $exception) {
                Response::jsonError($exception);
            }
        }
    }

    private static function recognizeRequestInControllerMethod($className, $methodName)
    {
        try {
            $r = new \ReflectionMethod($className, $methodName);
        } catch (\Exception $exception) {
            echo json_encode(['message'=>'Method not exists','code' => 404]);die;
        }
        $params = $r->getParameters();
        foreach ($params as $param) {
            //$param is an instance of ReflectionParameter
            $class = $param->getType()->getName();
            if(is_subclass_of($class,Request::class)) {
                return $class;
            }
        }
        return null;
    }

    private static function getURI(): array
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $path_info = $requestUri ? explode('?',ltrim($requestUri, '/'))[0] : '/';
        return explode('/', $path_info);
    }

    #[ArrayShape(['controller' => "string", 'method' => "mixed|string", 'args' => "array"])]
    private static function processURI(): array
    {
        $controllerPart = self::getURI()[0] ? ucfirst(self::getURI()[0]) : '';
        $methodPart = self::getURI()[1] ?? '';
        $numParts = count(self::getURI());
        $argsPart = [];
        for ($i = 2; $i < $numParts; $i++) {
            $argsPart[] = self::getURI()[$i] ?? '';
        }

        //Let's create some defaults if the parts are not set
        $controller = !empty($controllerPart) ?
            'App\\Controllers\\' . $controllerPart . 'Controller' :
            'App\\Controllers\HomeController';

        $method = !empty($methodPart) ?
            $methodPart :
            'index';

        $args = !empty($argsPart) ?
            $argsPart :
            [];

        return [
            'controller' => $controller,
            'method' => $method,
            'args' => $args
        ];
    }
}