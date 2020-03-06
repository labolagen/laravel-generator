<?php

namespace InfyOm\Generator\Generators\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Utils\FileUtil;

class LocaleGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $basePath;

    /** @var string */
    private $relPath;

    /** @var string */
    private $fileName;

    /** @var string */
    private $localeContents;

    /** @var string */
    private $localeTemplate;

    /** @var array */
    private $langs;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->basePath = config('infyom.laravel_generator.path.locale', resource_path('lang/'));
        $this->relPath = config('infyom.laravel_generator.prefixes.model_locale', 'models/');
        $this->fileName = $this->commandData->config->mSnakePlural.'.php';

        $this->localeTemplate = get_template('scaffold.locale', 'laravel-generator');

        $fields = [];
        foreach ($this->commandData->fields as $field) {
            $fields[$field->name] = Str::title(str_replace('_', ' ', $field->name));
        }
        $this->commandData->addDynamicVariable('$FIELDS$', var_export($fields, true));

        $this->localeContents = fill_template($this->commandData->dynamicVars, $this->localeTemplate);

        $langs = [config('app.local','en')];
        if ($this->commandData->getOption('localized')) {
            $langs = array_keys(config('locale.languages',$langs));
        }
        $this->langs = $langs;
    }

    public function generate()
    {
        foreach($this->langs as $lang){
            $parentDirectory = $this->basePath.$lang.DIRECTORY_SEPARATOR.$this->relPath;
            $fullPath = $parentDirectory.$this->fileName;
            if (file_exists($fullPath) && !$this->confirmOverwrite($fullPath)) {
                return;
            }

            FileUtil::createFile($parentDirectory, $this->fileName, $this->localeContents);
            $this->commandData->commandComment('Locale file('.$lang.') created.');
        }

        $this->commandData->commandComment("\nAll locale file created.");
    }

    public function rollback()
    {
        foreach($this->langs as $lang){
            $parentDirectory = $this->basePath.$lang.DIRECTORY_SEPARATOR.$this->relPath;
            $fullPath = $parentDirectory.$this->fileName;
            if (file_exists($fullPath)) {
                unlink($fullPath);
                $this->commandData->commandComment('Locale file('.$lang.') removed.');
            }
        }

        $this->commandData->commandInfo("\nAll locale file removed.");
    }
}
