<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
class GenerateCrudOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud operation with controller and views and set routes in routes/web';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = app('App\Models\\' . $name);
        $tableName = $model->getTable();
        $controllerName = $name . 'Controller';
        $routeName = (str_replace('_','-',$tableName));
        $viewsFolder = $tableName;
        $viewsFolderPath = 'admin';

        // Generate the controller
        $controllerTemplate = file_get_contents(base_path('resources/stubs/controller.stub'));
        $controllerContent = str_replace('CrudOperationController', $controllerName, $controllerTemplate);
        $controllerContent = str_replace('CrudOperationModel', $name, $controllerContent);
        file_put_contents(app_path("Http/Controllers/Admin/{$controllerName}.php"), $controllerContent);


        // Generate the views folder
        $fullViewPath = resource_path("views/{$viewsFolderPath}/{$viewsFolder}");
        if(!File::exists($fullViewPath)){
            File::makeDirectory($fullViewPath);
        }

        // Generate the views
        $this->generateView('index', "{$viewsFolderPath}/{$viewsFolder}");
        $this->generateView('edit', "{$viewsFolderPath}/{$viewsFolder}",'-edit');

        $this->info("Controller '{$controllerName}' and views for '{$viewsFolder}' generated successfully.");

        // Edit the routes file
        $this->editRoutesFile($routeName,$controllerName);
        $this->editSidebarFile($routeName);

        Artisan::call('optimize:clear');
        $this->info("Optimized successfully ;)");

    }

    private function generateView($viewName, $folderName, $edit='')
    {
        $viewTemplate = file_get_contents(base_path('resources/stubs/view'.$edit.'.stub'));
        $viewContent = str_replace('CrudOperationView', $viewName, $viewTemplate);
        file_put_contents(resource_path("views/{$folderName}/{$viewName}.blade.php"), $viewContent);
    }

    private function editRoutesFile($routeName ,$controllerName)
    {
        $routesFile = base_path('routes/crud.php');
        $newRoute = "\nRoute::resource('" . $routeName . "', $controllerName::class);\n";
        $newRoute .= "Route::get('dt/" . $routeName . "', '{$controllerName}@dataTable')->name('" . $routeName . ".datatable');\n\n";
        $marker = '// crud_operation_routes';

        $contents = file_get_contents($routesFile);
    
        // Find the position of the marker
        $markerPosition = strpos($contents, $marker);
        if ($markerPosition !== false) {
            // Insert the new route definition after the marker
            $updatedContents = substr_replace($contents, $newRoute, $markerPosition , 0);
            file_put_contents($routesFile, $updatedContents);
            $this->info("Route for '{$controllerName}' inserted after the marker '{$marker}'.");
        } else {
            $this->error("Marker '{$marker}' not found in the routes file. Unable to insert the route.");
        }
    
    }

    private function editSidebarFile($tableName )
    {
        $routesFile = resource_path('views/layouts/menu-links.blade.php');
        $newMenuLink = "\n<x-menu-link route_name=\"" . $tableName . ".index\" icon_class=\"mdi-chart-timeline-variant\"></x-menu-link>";
        $marker = '<!-- new menu-link -->';

        $contents = file_get_contents($routesFile);
    
        // Find the position of the marker
        $markerPosition = strpos($contents, $marker);
        if ($markerPosition !== false) {
            // Insert the new route definition after the marker
            $updatedContents = substr_replace($contents, $newMenuLink, $markerPosition + strlen($marker), 0);
            file_put_contents($routesFile, $updatedContents);
            $this->info("sidebar for '{$tableName}' inserted after the marker '{$marker}'.");
        } else {
            $this->error("Marker '{$marker}' not found in the sidebar file. Unable to insert the route.");
        }
    
    }
}
