<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Steex - Laravel 10 Admin & Dashboard Template'),
    'passed_otp' => env('APP_PASSED_OTP','7241'),
    'default_whatsapp_token' => env('APP_ULTRA_TOKEN','om7h2t171ory4n6m'),
    'default_whatsapp_instance_id' => env('APP_ULTRA_INSTANCE','instance69319'),
    'only_important_seeder_flag' => env('ONLY_IMPORTANT_SEEDER_FLAG',false),
    'sms_flag' => env('SMS_FLAG',false),
    'whatsapp_flag' => env('WHATSAPP_FLAG',false),
    'email_flag' => env('EMAIL_FLAG',false),
    'send_email_to_chairmans' => env('SEND_EMAIL_TO_CHAIRMANS',false),
    'current_app_version' => env('CURRENT_APP_VERSION',null),
    'white_list_wall_flag' => env('WHITE_LIST_WALL_FLAG',true),
    'white_list_wall_flag_numbers' => explode(',', env('WHITE_LIST_WALL_FLAG_NUMBERS','509165066,596938018,570044066,557436279,530410927,544021418,565603434,506512136,567177400,548116076,506011819')),
    'supervisor_pass_white_list_wall_flag' => env('SUPERVISOR_PASS_WHITE_LIST_WALL_FLAG',true),
    'stop_downloaded_audit' => env('STOP_DOWNLOADED_AUDIT',false),
    'return_otp_in_response' => env('RETURN_OTP_IN_RESPONSE',false),
    'ota_flag' => env('OTA_FLAG',false),
    'handover_provider_form_ids' => explode(',',env('HANDOVER_PROVIDER_FORM_IDS',", ")),
    'meals_preparation_pusher' => env('MEALS_PREPARATION_PUSHER', true),
    'use_monitor_code' => env('APP_USE_MONITOR_CODE', false),
    'show_looker_studio_reports' => env('SHOW_LOOKER_STUDIO_REPORTS', false),


    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Riyadh',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        OwenIt\Auditing\AuditingServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,
        Alkoumi\LaravelHijriDate\LaravelHijriDateServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
    ])->toArray(),

];
