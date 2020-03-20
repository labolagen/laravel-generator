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
        $this->routeContents = file_get_contents($this->path);
        if (Str::contains($this->routeContents, "Route::resource('".$this->commandData->config->mSnakePlural."',")) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.'is already exists, Skipping Adjustment.');

            return;
        }

        $this->routeContents .= $this->routesTemplate."\n";

        file_put_contents($this->path, $this->routeContents);

        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' api routes added.');
    }

    public function rollback()
    {
        $this->routeContents = file_get_contents($this->path);
        if (file_exists($this->path)) {
            $routeContents = str_replace($this->routesTemplate."\n",'', $this->routeContents);

            // If route content length has changed after replacement,
            // consider that route has been removed.
            if(strlen($this->routeContents) != strlen($routeContents)){
                file_put_contents($this->path, $routeContents);
                $this->commandData->commandComment('api routes deleted');
            }
        }
    }
}
