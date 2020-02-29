<?php

namespace InfyOm\Generator\Generators\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;

class MenuGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $menuContents;

    /** @var string */
    private $menuTemplate;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = config(
            'infyom.laravel_generator.path.views',
            resource_path('views/'
            )
        );//.$commandData->getAddOn('menu.menu_folder');
        $this->templateType = config('infyom.laravel_generator.templates', 'coreui-templates');

        $templateName = 'menu_item_template';

        if ($this->commandData->isLocalizedTemplates()) {
            $templateName .= '_locale';
        }

        $this->menuTemplate = get_template('scaffold.layouts.'.$templateName, $this->templateType);

        $this->path .= $commandData->getAddOn('menu.menu_file');
        $this->menuContents = fill_template($this->commandData->dynamicVars, $this->menuTemplate);
    }

    public function generate()
    {
        $menuFilePath = $this->path.$commandData->getAddOn('menu.menus_folder');
        $fileName = $this->commandData->config->mPlural.'.blade.php';
        if (file_exists($menuFilePath.$fileName) && $this->confirm("\nDo you want to overwrite ".$this->commandData->config->mPlural." menu? [y|N]", false)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.' already exists, Skipping Adjustment.');

            return;
        }

        FileUtil::createFile($this->path, $fileName, $this->routesTemplate);
        $this->commandData->commandComment("\nAdded ".$this->commandData->config->mCamelPlural.' to menu.');

        $this->insertIntoMenuBlade();
    }

    public function insertIntoMenuBlade(){
        $menuBladePath = $this->path.$commandData->getAddOn('menu.menu_file');
        $existingMenuContents = file_get_contents($menuBladePath);
        $menuIncludeContent = '@include(\''.str_replce('/','.',$commandData->getAddOn('menu.menu_file')).$this->commandData->config->mHumanPlural.'\')';
        if (Str::contains($existingMenuContents, $menuIncludeContent)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mHumanPlural.' already exists in menu.blade, Skipping Adjustment.');

            return;
        }

        file_put_contents($menuBladePath, $existingMenuContents."\n".$menuIncludeContent);
        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' menu added to menu.blade.');

    }

    public function rollback()
    {
        if (Str::contains($this->menuContents, $this->menuTemplate)) {
            file_put_contents($this->path, str_replace($this->menuTemplate, '', $this->menuContents));
            $this->commandData->commandComment('menu deleted');
        }
    }
}
