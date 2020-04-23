<?php

namespace Attla;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\ViewServiceProvider;

class Blade implements FactoryContract
{
    /**
     * @var Application
     */
    protected $container;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var BladeCompiler
     */
    private $compiler;

    public function __construct($viewPaths, $cachePath, $container = null){
        $this->container = $container ?: new Container;

        $this->setupContainer((array) $viewPaths, $cachePath);
        (new ViewServiceProvider($this->container))->register();

        $this->factory = $this->container->get('view');
        $this->compiler = $this->container->get('blade.compiler');
    }

    public function render($view, $data = [], $mergeData = []){
        return $this->make($view, $data, $mergeData)->render();
    }

    public function make($view, $data = [], $mergeData = []){
        return $this->factory->make($view, $data, $mergeData);
    }

    public function compiler(){
        return $this->compiler;
    }

    public function directive($name, $handler){
        $this->compiler->directive($name, $handler);
    }
    
    public function if($name, $callback){
        $this->compiler->if($name, $callback);
    }

    public function exists($view){
        return $this->factory->exists($view);
    }

    public function file($path, $data = [], $mergeData = []){
        return $this->factory->file($path, $data, $mergeData);
    }

    public function share($key, $value = null){
        return $this->factory->share($key, $value);
    }

    public function composer($views, $callback){
        return $this->factory->composer($views, $callback);
    }

    public function creator($views, $callback){
        return $this->factory->creator($views, $callback);
    }

    public function addNamespace($namespace, $hints){
        $this->factory->addNamespace($namespace, $hints);

        return $this;
    }

    public function replaceNamespace($namespace, $hints){
        $this->factory->replaceNamespace($namespace, $hints);

        return $this;
    }

    public function __call($method, $params){
        return call_user_func_array([$this->factory, $method], $params);
    }

    protected function setupContainer($viewPaths, $cachePath){
        $this->container->bindIf('files', function () {
            return new Filesystem;
        }, true);

        $this->container->bindIf('events', function () {
            return new Dispatcher;
        }, true);

        $this->container->bindIf('config', function () use ($viewPaths, $cachePath) {
            return [
                'view.paths' => $viewPaths,
                'view.compiled' => $cachePath,
            ];
        }, true);
        
        Facade::setFacadeApplication($this->container);
    }
}
