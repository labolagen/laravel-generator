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
    private $filename;

    /** @var string */
    private $templateType;

    /** @var string */
    private $menuContents;

    /** @var string */
    private $menuTemplate;

    /** @var string */
    private $menuIncludeContent;

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
        $this->filename = $this->commandData->config->mPlural.'.blade.php';

        $this->menuTemplate = get_template('scaffold.layouts.'.$templateName, $this->templateType);

        $this->path .= $commandData->getAddOn('menu.menu_file');
        $this->menuContents = fill_template($this->commandData->dynamicVars, $this->menuTemplate);
        $this->menuIncludeContent = '@include(\''.str_replce('/','.',$commandData->getAddOn('menu.menu_file')).$this->commandData->config->mHumanPlural.'\')';
    }

    public function generate()
    {
        $menuFilePath = $this->path.$commandData->getAddOn('menu.menus_folder');
        if (file_exists($menuFilePath.$this->fileName) && $this->confirm("\nDo you want to overwrite ".$this->commandData->config->mPlural." menu? [y|N]", false)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.' already exists, Skipping Adjustment.');

            return;
        }

        FileUtil::createFile($this->path, $this->fileName, $this->menuContents);
        $this->commandData->commandComment("\nAdded ".$this->commandData->config->mCamelPlural.' to menu.');

        $this->insertIntoMenuBlade();
    }

    public function insertIntoMenuBlade(){
        $menuBladePath = $this->path.$commandData->getAddOn('menu.menu_file');
        $existingMenuContents = file_get_contents($menuBladePath);
        if (Str::contains($existingMenuContents, $this->menuIncludeContent)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mHumanPlural.' already exists in menu.blade, Skipping Adjustment.');

            return;
        }

        file_put_contents($menuBladePath, $existingMenuContents."\n".$this->menuIncludeContent);
        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' menu added to menu.blade.');

    }

    public function rollback()
    {
        $existingMenuContents = file_get_contents($menuBladePath);
        if (Str::contains($this->menuContents, $this->menuTemplate)) {
            file_put_contents($this->path, str_replace($this->menuIncludeContent, '', $existingMenuContents));
            $this->commandData->commandComment('menu deleted');
        }

        if(file_exists($this->filename)){
            unlink($this->filename);
            $this->commandData->commandObj->info('Deleted '.$this->filename.'.');
        }
    }
}
