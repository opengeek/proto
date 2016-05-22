<?php
/*
 * This file is part of the proto package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenGeek\Proto\Controller;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Collection;
use Slim\Http\Uri;
use Slim\Route;
use Slim\Router;
use Slim\Views\Twig;
use Twig_Error_Loader;

class View
{
    protected static $name = 'View';
    protected static $excludes = [];
    protected static $prefix = '';

    /**
     * @var Twig
     */
    protected $twig;

    /**
     * @var array
     */
    protected $data = [];

    public static function name()
    {
        return static::$name;
    }

    public static function excludes(array $excludes = [])
    {
        return array_merge(['^base$', '^404$', '^macros'], static::$excludes, $excludes);
    }

    public static function url(Router $router, ServerRequestInterface $request, array $routeParams, $params = false)
    {
        /** @var Route $route */
        $route = $request->getAttribute('route');

        $getParams = [];
        if (false !== $params) {
            $getParams = $route->getName() === static::name()
                ? $request->getQueryParams()
                : [];
            $getParams = array_merge($getParams, is_array($params) ? $params : []);
        }

        return $router->pathFor(static::name(), $routeParams, $getParams);
    }

    public function __construct(Twig $twig, Uri $uri, Collection $config)
    {
        $this->twig = $twig;
        $this->data = [
            'rootUri' => $uri->getBasePath(),
            'resourceUri' => $uri,
            'year' => strftime('%Y'),
            'config' => $config->all()
        ];
    }

    public function viewExists(array $args, array $excludes = [])
    {
        $viewTemplate = static::$prefix . trim(implode('/', $args), '/');
        foreach (static::excludes($excludes) as $exclude) {
            if (preg_match("#{$exclude}#i", $viewTemplate)) {
                return false;
            }
        }
        try {
            $this->twig->getLoader()->getCacheKey($viewTemplate . '.twig');
        } catch (Twig_Error_Loader $e) {
            return false;
        }

        return true;
    }

    public function appendData(array $data)
    {
        $this->data = array_replace_recursive($this->data, $data);
    }

    public function handle(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = array_filter((array)$request->getAttribute('params', []));
        if (empty($params)) {
            $params = ['index'];
        }

        return $this->twig->render($response, static::$prefix . trim(implode('/', $params), '/') . '.twig', $this->data);
    }
}
