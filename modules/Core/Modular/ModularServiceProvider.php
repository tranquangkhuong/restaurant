<?php

namespace Modules\Core\Modular;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Modular\Console\CreateControllerCmd;
use Modules\Core\Modular\Console\CreateModelCmd;
use Modules\Core\Modular\Console\CreateRepositoryCmd;
use Modules\Core\Modular\Console\CreateResourceCmd;
use Modules\Core\Modular\Console\CreateServiceCmd;
use Modules\Core\Modular\Console\MakeModuleCmd;

class ModularServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands('modules.make');
        $bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';
        $this->app->{$bind_method}('modules.make', function ($app) {
            return new MakeModuleCmd(new Filesystem());
        });

        //Single commands available
        $this->commands([
            CreateControllerCmd::class,
            CreateModelCmd::class,
            CreateRepositoryCmd::class,
            CreateResourceCmd::class,
            CreateServiceCmd::class,
        ]);
    }
}
