<?php

namespace App\Providers;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Knuckles\Scribe\Scribe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);
        // Blade::component('question-component', Question::class);
        // Relation::morphMap([
        //     'OrganizationService' => 'App\Models\OrganizationService',
        //     'User' => 'App\Models\User',
        // ]);
        Validator::extend('unique_soft_delete', function ($attribute, $value, $parameters, $validator) {
            $table = $parameters[0] ?? null;
            $column = $parameters[1] ?? null;
            $id = $parameters[2] ?? null;
            if (!$table || !$column) {
                return false;
            }
            // dd($table, $column, $value,DB::table($table)->where($column, $value)->whereNull('deleted_at')->where('id', '!=', $id)->count(),$id);
            return DB::table($table)->where($column, $value)->whereNull('deleted_at')->where('id', '!=', $id)->count() === 0;
        });

        Scribe::normalizeEndpointUrlUsing(fn($url) => $url);
    }
}
