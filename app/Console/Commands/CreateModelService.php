<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class CreateModelService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {model}';

    /**
     * The console command create a new service for a model.
     *
     * @var string
     */
    protected $description = 'Create a new service for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $servicePath = app_path('Services/' . $model . 'Service.php');

        if (File::exists($servicePath)) {
            $this->error($model . 'Service already exists!');
            return;
        }

        $serviceTemplate = str_replace( ['{{modelName}}', '{{modelNamePlural}}'],
                                        [ $model,  Str::plural($model)],
                                        File::get(base_path('stubs/service.stub')));

        File::put($servicePath, $serviceTemplate);

        $this->info($model . 'Service created successfully!');
    }
}
