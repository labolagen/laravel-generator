<?php

namespace Labolagen\Generator\Commands\Publish;

use Illuminate\Support\Str;
use Labolagen\Generator\Utils\FileUtil;
use Symfony\Component\Console\Input\InputOption;

class LayoutPublishCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'labolagen.publish:layout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes auth files';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->copyView();
    }

    private function copyView()
    {
        $viewsPath = config('labolagen.laravel_generator.path.views', resource_path('views/'));
        $templateType = config('labolagen.laravel_generator.templates', 'coreui-templates');

        $this->createDirectories($viewsPath);

        if ($this->option('localized')) {
            $files = $this->getLocaleViews();
        } else {
            $files = $this->getViews();
        }

        foreach ($files as $stub => $blade) {
            $sourceFile = get_template_file_path('scaffold/'.$stub, $templateType);
            $destinationFile = $viewsPath.$blade;
            $this->publishFile($sourceFile, $destinationFile, $blade);
        }
    }

    private function createDirectories($viewsPath)
    {
        FileUtil::createDirectoryIfNotExist($viewsPath.'layouts');
    }

    private function getViews()
    {
        $views = [
            'layouts/sidebar'           => 'backend/includes/sidebar.blade.php',
        ];

        return $views;
    }

    private function getLocaleViews()
    {
        return [
            'layouts/sidebar_locale'    => 'backend/includes/sidebar.blade.php',
        ];
    }

    /**
     * Replaces dynamic variables of template.
     *
     * @param string $templateData
     *
     * @return string
     */
    private function fillTemplate($templateData)
    {
        $templateData = str_replace(
            '$NAMESPACE_BACKEND_CONTROLLER$',
            config('labolagen.laravel_generator.namespace.backend_controller'), $templateData
        );

        $templateData = str_replace(
            '$NAMESPACE_FRONTEND_CONTROLLER$',
            config('labolagen.laravel_generator.namespace.frontend_controller'), $templateData
        );

        $templateData = str_replace(
            '$NAMESPACE_REQUEST$',
            config('labolagen.laravel_generator.namespace.request'), $templateData
        );

        return $templateData;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['localized', null, InputOption::VALUE_NONE, 'Localize files.'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
