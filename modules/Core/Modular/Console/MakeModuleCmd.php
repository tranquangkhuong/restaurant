<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Command tạo module
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class MakeModuleCmd extends Command
{

    protected $signature = 'create:module {moduleName} {--packet=Staff}';
    protected $filesystem;
    protected $description = 'Tạo một module mới';
    protected $packet;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->dirModular = base_path() . '/modules/Core/Modular/';
    }

    /**
     * Thực thi lệnh cmd
     */
    public function handle()
    {
        $path = base_path();
        $packet = $this->option('packet');
        $this->packet = $packet;
        $path = $path . '\\modules\\' . $packet;

        // Kiểm tra module
        if (!$this->filesystem->isDirectory($path)) {
            return $this->error('Layout ' . $packet . ' chưa tồn tại trong hệ thống!');
        }

        $this->genController($path);
        $this->genModel($path);
        $this->genRepository($path);
        $this->genService($path);
        $this->genResource($path);
    }

    /**
     * Tạo Tontroller
     */
    public function genController($path)
    {
        $path = $path . "\\Controllers";
        if (!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $filePath = str_replace('\\', '/', $path . "/" . $this->getModuleName() . "Controller.php");
        if (is_file($filePath)) {
            echo $this->error('File ' . $this->getModuleName() . 'Controller.php đã tồn tại trong hệ thống!');
        } else {
            $stubs  = file_get_contents($this->dirModular . 'stubs/controller.stub');
            $this->createFile($filePath, $stubs, 'Controller.php');
        }
    }

    /**
     * Tạo Model
     */
    public function genModel($path)
    {
        $path = $path . "\\Models";
        if (!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $filePath = str_replace('\\', '/', $path . "/" . $this->getModuleName() . "Model.php");
        if (is_file($filePath)) {
            echo $this->error('File ' . $this->getModuleName() . 'Model.php đã tồn tại trong hệ thống!');
        } else {
            $stubs  = file_get_contents($this->dirModular . 'stubs/model.stub');
            $this->createFile($filePath, $stubs, 'Model.php');
        }
    }

    /**
     * Tạo Repository
     */
    public function genRepository($path)
    {
        $path = $path . "/Repositories";
        if (!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $filePath = str_replace('\\', '/', $path . "/" . $this->getModuleName() . "Repository.php");
        if (is_file($filePath)) {
            echo $this->error('File ' . $this->getModuleName() . 'Repository.php đã tồn tại trong hệ thống!');
        } else {
            $stubs  = file_get_contents($this->dirModular . 'stubs/repository.stub');
            $this->createFile($filePath, $stubs, 'Repository.php');
        }
    }

    /**
     * Tạo Service
     */
    public function genService($path)
    {
        $path = $path . "/Services";
        if (!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $filePath = str_replace('\\', '/', $path . "/" . $this->getModuleName() . "Service.php");
        if (is_file($filePath)) {
            echo $this->error('File ' . $this->getModuleName() . 'Service.php đã tồn tại trong hệ thống!');
        } else {
            $stubs  = file_get_contents($this->dirModular . 'stubs/service.stub');
            $this->createFile($filePath, $stubs, 'Service.php');
        }
    }

    /**
     * Tạo Resource
     */
    public function genResource($path)
    {
        $path = $path . "/Resources";
        if (!$this->filesystem->isDirectory($path))
            $this->filesystem->makeDirectory($path, 0777, true, true);
        $filePath = str_replace('\\', '/', $path . "/" . $this->getModuleName() . "Resource.php");
        if (is_file($filePath)) {
            echo $this->error('File ' . $this->getModuleName() . 'Resource.php đã tồn tại trong hệ thống!');
        } else {
            $stubs  = file_get_contents($this->dirModular . 'stubs/resource.stub');
            $this->createFile($filePath, $stubs, 'Resource.php');
        }
    }

    /**
     * Tạo thành file
     */
    public function createFile($filePath, $stubs, $suffixes)
    {
        $packet = $this->packet;
        $stub = str_replace('{{MODULE_NAME}}', $this->getModuleName(),  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $packet, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($this->getModuleName()), $stub);
        $stub = str_replace('{{DATE_CREATE}}', date('d/m/Y'), $stub);

        if (!is_file($filePath)) $this->filesystem->put($filePath, $stub);
        $this->info('Đã tạo thành công: ' . $this->getModuleName() . $suffixes);
    }

    /**
     * Get value of name input argument
     *
     * @return String
     */
    public function getModuleName()
    {
        return ucfirst($this->argument('moduleName'));
    }
}
