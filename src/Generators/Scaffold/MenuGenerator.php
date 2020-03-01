<?php

namespace InfyOm\Generator\Generators\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class MenuGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $menuBladePath;

    /** @var string */
    private $menuFilePath;

    /** @var string */
    private $fileName;

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
        $basepath = config(
            'infyom.laravel_generator.path.views',
            resource_path('views/'
            )
        );
        $this->templateType = config('infyom.laravel_generator.templates', 'coreui-templates');

        $templateName = 'menu_item_template';

        if ($this->commandData->isLocalizedTemplates()) {
            $templateName .= '_locale';
        }

        $this->menuTemplate = get_template('scaffold.layouts.'.$templateName, $this->templateType);

        $this->menuFilePath = $basepath.$this->commandData->getAddOn('menu.menus_folder');
        $this->menuBladePath = $basepath.$this->commandData->getAddOn('menu.menu_file');
        $this->fileName = $this->commandData->config->mSnakePlural.'.blade.php';

        $this->menuContents = fill_template($this->commandData->dynamicVars, $this->menuTemplate);
        $this->menuIncludeContent = '@include(\''.str_replace('/','.',$this->commandData->getAddOn('menu.menus_folder')).$this->commandData->config->mSnakePlural.'\')';
    }

    public function generate()
    {
        if (file_exists($this->menuFilePath.$this->fileName)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.' already exists, Skipping Adjustment.');

            return;
        }

        FileUtil::createFile($this->menuFilePath, $this->fileName, $this->menuContents);
        $this->commandData->commandComment("\nAdded ".$this->commandData->config->mCamelPlural.' to menu.');

        $this->insertIntoMenuBlade();
    }

    public function insertIntoMenuBlade(){
        $existingMenuContents = file_get_contents($this->menuBladePath);
        if (Str::contains($existingMenuContents, $this->menuIncludeContent)) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mHumanPlural.' already exists in menu.blade, Skipping Adjustment.');

            return;
        }

        file_put_contents($this->menuBladePath, $existingMenuContents."\n".$this->menuIncludeContent);
        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' menu added to menu.blade.');

    }

    public function rollback()
    {
        $existingMenuContents = file_get_contents($this->menuBladePath);
        if (Str::contains($existingMenuContents, $this->menuIncludeContent)) {
            file_put_contents($this->menuBladePath, str_replace($this->menuIncludeContent, '', $existingMenuContents));
            $this->commandData->commandComment('menu deleted');
        }

        if(file_exists($this->menuFilePath.$this->fileName)){
            unlink($this->menuFilePath.$this->fileName);
            $this->commandData->commandObj->info('Deleted '.$this->fileName.'.');
        }
    }
}
