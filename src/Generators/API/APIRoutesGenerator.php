<?php

namespace Labolagen\Generator\Generators\API;

use Illuminate\Support\Str;
use Labolagen\Generator\Common\CommandData;
use Labolagen\Generator\Generators\BaseGenerator;

class APIRoutesGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $routeContents;

    /** @var string */
    private $routesTemplate;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiRoutes;

        if (!empty($this->commandData->config->prefixes['route'])) {
            $routesTemplate = get_template('api.routes.prefix_routes', 'laravel-generator');
        } else {
            $routesTemplate = get_template('api.routes.routes', 'laravel-generator');
        }

        $this->routesTemplate = fill_template($this->commandData->dynamicVars, $routesTemplate);
    }

    public function generate()
    {
        dump(1);
        $this->routeContents = file_get_contents($this->path);
        $this->routeContents .= "\n\n".$this->routesTemplate;
        $existingRouteContents = file_get_contents($this->path);
        if (Str::contains($existingRouteContents, "Route::resource('".$this->commandData->config->mSnakePlural."',")) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.'is already exists, Skipping Adjustment.');

            return;
        }

        file_put_contents($this->path, $this->routeContents);

        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' api routes added.');
    }

    public function rollback()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
            $this->commandData->commandComment('api routes deleted');
        }
    }
}
