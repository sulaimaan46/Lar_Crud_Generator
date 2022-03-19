<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\fileExists;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator {name : Class (singular) for example User} {api: yes or no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $api = $this->argument('api');

        $this->model($name);
        $this->controller($name);
        $this->request($name);

        if(!file_exists($modelPath = base_path("packages/crud/src/routes"))){
            mkdir($modelPath,0777,true);
        }

        if($api == 'yes'){
            File::put(base_path('packages/crud/src/routes/').'api.php',
                '<?php
                use Illuminate\Support\Facades\Route;

                Route::resource(\''.Str::plural($name)."','{$name}Controller');"
            );
        }

        File::put(base_path('packages/crud/src/routes/').'web.php',
                '<?php
                use Illuminate\Support\Facades\Route;

                Route::resource(\''.Str::plural($name)."','{$name}Controller');"
        );

        Artisan::call('make:migration create_'.strtolower(Str::plural($name)).'_table --create='.strtolower(Str::plural($name)));

        $this->info('Model Created Sucessfully');
        $this->info('Controller Created Sucessfully');
        $this->info('Request Created Sucessfully');
        $this->info('Routes Created Sucessfully');
        $this->info('Migration Created Sucessfully');
        $this->info('CRUD files generated successfully!');

    }

    protected function getStub($type){
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function model($name){
        $template = str_replace(
            [
                '{{modelName}}',
                '{{packagePath}}',
            ],
            [
                $name,
                'HP\CrudGenrator'
            ],
            $this->getStub('Model')
        );

        if(!file_exists($modelPath = base_path("/packages/crud/src/Models"))){
            mkdir($modelPath,0777,true);
        }
        file_put_contents(base_path("/packages/crud/src/Models/{$name}.php"),$template);

    }

    protected function request($name){
        $template = str_replace(
            ['{{modelName}}',
             '{{packagePath}}',
            ],
            [
                $name,
                'HP\CrudGenrator'
            ],
            $this->getStub('Request')
        );

        if(!file_exists($modelPath = base_path("/packages/crud/src/Requests"))){
            mkdir($modelPath,0777,true);
        }

        file_put_contents(base_path("/packages/crud/src/Requests/{$name}Request.php"),$template);
    }

    protected function controller($name){
        $template = str_replace(
            [   '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{packagePath}}',
            ],
            [
                $name,  //Post
                strtolower(Str::plural($name)),  //posts
                strtolower($name),   //post
                'HP\CrudGenrator'
            ],
            $this->getStub('Controller')
        );

        if(!file_exists($modelPath = base_path("/packages/crud/src/Http/Controllers"))){
            mkdir($modelPath,0777,true);
        }

        file_put_contents(base_path("/packages/crud/src/Http/Controllers/{$name}Controller.php"),$template);

    }
}
