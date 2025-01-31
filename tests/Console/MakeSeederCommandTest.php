<?php

use Illuminate\Filesystem\Filesystem;

beforeEach(function () {
    $this->artisan('modular:make-module ModuleName');
});

afterEach(function () {
    (new Filesystem)->deleteDirectory(base_path('modules'));
});

it('can run modular:make-seeder command', function () {
    $this->artisan('modular:make-seeder moduleName resourceName')->assertSuccessful();
});

it('can generate a seeder', function () {
    $this->artisan('modular:make-seeder moduleName resourceName');

    $seeder = base_path('modules/ModuleName/Database/Seeders/ResourceName.php');
    $this->assertTrue(file_exists($seeder));

    $seederContent = file_get_contents($seeder);

    expect($seederContent)->toContain('namespace Modules\ModuleName\Database\Seeders;');
    expect($seederContent)->toContain('class ResourceName extends Seeder');
});
