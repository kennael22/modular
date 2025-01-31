<?php

namespace Modular\Modular\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Modular\Modular\Console\InstallerTraits\ModuleExists;

class MakeSeederCommand extends Command
{
    use ModuleExists;

    protected $signature = 'modular:make-seeder {moduleName} {resourceName}';

    protected $description = 'Create a new seeder class for a Module';

    protected string $moduleName;

    protected string $resourceName;

    public function handle(): int
    {
        $this->moduleName = Str::studly($this->argument('moduleName'));
        $this->resourceName = Str::studly($this->argument('resourceName'));

        if (! $this->moduleExists()) {
            return self::FAILURE;
        }

        $this->comment('Module '.$this->moduleName.' found, creating seeder file...');
        $this->createModuleSeeder();

        return self::SUCCESS;
    }

    private function createModuleSeeder(): void
    {
        (new Filesystem)->ensureDirectoryExists(base_path("modules/{$this->moduleName}/Database/Seeders"));

        $stub = file_get_contents(__DIR__.'/../../stubs/module-stub/modules/Database/Seeders/ModuleSeeder.stub');

        $stub = str_replace('{{ ModuleName }}', $this->moduleName, $stub);
        $stub = str_replace('{{ ResourceName }}', $this->resourceName, $stub);

        $path = base_path("modules/{$this->moduleName}/Database/Seeders/{$this->resourceName}.php");

        file_put_contents($path, $stub);
    }
}
