<?php

namespace Modules\Core;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    protected $namespace;
    protected $module;
    protected $prefix;
    protected $middleware;

    public function boot()
    {
        $layouts = config('modules.layouts');
        // check url thuộc layout nào
        foreach ($layouts as $key => $value) {
            if (Request::is($key) || Request::is($key.'/*')) {
                $this->bootApi($value, $key);
            }
        }
    }

    public function bootApi($layout, $url)
    {
        $this->namespace = 'Modules\\'.$layout.'\\Controllers';
        $this->module = $url;
        $this->prefix = $url;
        $this->middleware = ['web'];
        // load routes
        Route::group([
            'namespace' => $this->namespace,
            'module' => $this->module,
            'prefix' => strtolower($this->prefix),
        ], function() {
            $this->loadRoutesFrom(base_path().'\\modules\\'.$this->module.'\\routes.php');
        });
    }
}
