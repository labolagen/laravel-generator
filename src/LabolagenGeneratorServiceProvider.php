<?php

namespace Labolagen\Generator;

use Illuminate\Support\ServiceProvider;
use Labolagen\Generator\Commands\API\APIControllerGeneratorCommand;
use Labolagen\Generator\Commands\API\APIGeneratorCommand;
use Labolagen\Generator\Commands\API\APIRequestsGeneratorCommand;
use Labolagen\Generator\Commands\API\TestsGeneratorCommand;
use Labolagen\Generator\Commands\APIScaffoldGeneratorCommand;
use Labolagen\Generator\Commands\Common\MigrationGeneratorCommand;
use Labolagen\Generator\Commands\Common\ModelGeneratorCommand;
use Labolagen\Generator\Commands\Common\RepositoryGeneratorCommand;
use Labolagen\Generator\Commands\Publish\GeneratorPublishCommand;
use Labolagen\Generator\Commands\Publish\LayoutPublishCommand;
use Labolagen\Generator\Commands\Publish\PublishTemplateCommand;
use Labolagen\Generator\Commands\Publish\PublishUserCommand;
use Labolagen\Generator\Commands\RollbackGeneratorCommand;
use Labolagen\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use Labolagen\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use Labolagen\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use Labolagen\Generator\Commands\Scaffold\ViewsGeneratorCommand;

class LabolagenGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/laravel_generator.php';

        $this->publishes([
            $configPath => config_path('labolagen/laravel_generator.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('labolagen.publish', function ($app) {
            return new GeneratorPublishCommand();
        });

        $this->app->singleton('labolagen.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('labolagen.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('labolagen.publish.layout', function ($app) {
            return new LayoutPublishCommand();
        });

        $this->app->singleton('labolagen.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('labolagen.api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand();
        });

        $this->app->singleton('labolagen.migration', function ($app) {
            return new MigrationGeneratorCommand();
        });

        $this->app->singleton('labolagen.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('labolagen.repository', function ($app) {
            return new RepositoryGeneratorCommand();
        });

        $this->app->singleton('labolagen.api.controller', function ($app) {
            return new APIControllerGeneratorCommand();
        });

        $this->app->singleton('labolagen.api.requests', function ($app) {
            return new APIRequestsGeneratorCommand();
        });

        $this->app->singleton('labolagen.api.tests', function ($app) {
            return new TestsGeneratorCommand();
        });

        $this->app->singleton('labolagen.scaffold.controller', function ($app) {
            return new ControllerGeneratorCommand();
        });

        $this->app->singleton('labolagen.scaffold.requests', function ($app) {
            return new RequestsGeneratorCommand();
        });

        $this->app->singleton('labolagen.scaffold.views', function ($app) {
            return new ViewsGeneratorCommand();
        });

        $this->app->singleton('labolagen.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->app->singleton('labolagen.publish.user', function ($app) {
            return new PublishUserCommand();
        });

        $this->commands([
            'labolagen.publish',
            'labolagen.api',
            'labolagen.scaffold',
            'labolagen.api_scaffold',
            'labolagen.publish.layout',
            'labolagen.publish.templates',
            'labolagen.migration',
            'labolagen.model',
            'labolagen.repository',
            'labolagen.api.controller',
            'labolagen.api.requests',
            'labolagen.api.tests',
            'labolagen.scaffold.controller',
            'labolagen.scaffold.requests',
            'labolagen.scaffold.views',
            'labolagen.rollback',
            'labolagen.publish.user',
        ]);
    }
}
