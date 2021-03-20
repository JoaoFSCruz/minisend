<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('max_upload_file_size', function ($attribute, $value, $parameters, $validator) {
            $totalSize = array_reduce($value, static function ($sum, $file) {
                $sum += $file->getSize();
                return $sum;
            });

            return $totalSize < $parameters[0] * 1024;
        });
    }
}
