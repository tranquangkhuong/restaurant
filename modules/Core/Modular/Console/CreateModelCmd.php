<?php

namespace Modules\Core\Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateModelCmd extends Command
{
    protected $signature  = 'create:module-model {moduleName} {--packet=Staff}';
    protected $description = 'Tạo model cho Module';
    protected $filesystem;


    public function __construct()
    {
        parent::__construct();
        $this->filesystem = new Filesystem();
    }

    /**
     * Thực thi tạo model
     */
    public function handle()
    {
        $packet = $this->option('packet');
        $path = base_path();
        $moduleName = ucfirst($this->argument('moduleName'));
        $path = $path . '\\modules\\' . $packet;
        // Kiểm tra module
        if (!$this->filesystem->isDirectory($path)) {
            return $this->error('Layout ' . $packet . ' chưa tồn tại trong hệ thống!');
        }
        // Kiểm tra thư mục models
        if (!$this->filesystem->isDirectory($path . '\\Models'))
            $this->filesystem->makeDirectory($path . '\\Models', 0777, true, true);
        $filePath = $path . '\\Models\\' . $moduleName . 'Model.php';
        // check if file exists
        if (is_file($filePath))
            return $this->error('File ' . $moduleName . 'Model.php đã tồn tại trong hệ thống!');
        $stubs  = file_get_contents(base_path() . '/modules/Core/Modular/stubs/model.stub');
        $stub = str_replace('{{MODULE_NAME}}', $moduleName,  $stubs);
        $stub = str_replace('{{LAYOUT_NAME}}', $packet, $stub);
        $stub = str_replace('{{MODULE_NAME_LOWER}}', lcfirst($moduleName), $stub);
        $stub = str_replace('{{DATE_CREATE}}', date('d/m/Y'), $stub);
        $this->filesystem->put($filePath, $stub);

        $this->info('Tạo thành công: ' . $moduleName . 'Model.php');
    }
}
