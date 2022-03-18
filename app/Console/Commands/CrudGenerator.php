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
    protected $signature = 'crud:generator {name : Class (singular) for example User}';

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

        $this->model($name);
        $this->controller($name);
        $this->request($name);

        File::append(base_path('routes/web.php'),'Route::resource(\''.Str::plural($name)."','{$name}Controller');");
        File::append(base_path('routes/api.php'),'Route::resource(\''.Str::plural($name)."','{$name}Controller');");

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
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"),$template);
    }

    protected function request($name){
        $template = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
        );

        if(!file_exists($path = app_path('/Http/Requests'))){
            mkdir($path,0777,true);
        }

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"),$template);
    }

    protected function controller($name){
        $template = str_replace(
            [   '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $name,  //Post
                strtolower(Str::plural($name)),  //posts
                strtolower($name),   //post
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"),$template);

    }
}
