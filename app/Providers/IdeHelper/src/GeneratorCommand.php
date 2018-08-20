<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/7/3
 * Time: 10:37
 */

namespace App\Providers\IdeHelper;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Barryvdh\LaravelIdeHelper\Console\GeneratorCommand as BaseGeneratorCommand;

class GeneratorCommand extends BaseGeneratorCommand
{
    public function handle()
    {
        if (file_exists(base_path() . '/vendor/compiled.php') ||
            file_exists(base_path() . '/bootstrap/cache/compiled.php') ||
            file_exists(base_path() . '/storage/framework/compiled.php')) {
            $this->error(
                'Error generating IDE Helper: first delete your compiled file (php artisan clear-compiled)'
            );
        } else {
            $filename = $this->argument('filename');
            $format = $this->option('format');

            // Strip the php extension
            if (substr($filename, -4, 4) == '.php') {
                $filename = substr($filename, 0, -4);
            }

            $filename .= '.' . $format;

            if ($this->option('memory')) {
                $this->useMemoryDriver();
            }


            $helpers = '';
            if ($this->option('helpers') || ($this->config->get('ide-helper.include_helpers'))) {
                foreach ($this->config->get('ide-helper.helper_files', array()) as $helper) {
                    if (file_exists($helper)) {
                        $helpers .= str_replace(array('<?php', '?>'), '', $this->files->get($helper));
                    }
                }
            } else {
                $helpers = '';
            }

            $generator = new Generator($this->config, $this->view, $this->getOutput(), $helpers);
            $content = $generator->generate($format);
            $written = $this->files->put($filename, $content);

            if ($written !== false) {
                $this->info("A new helper file was written to $filename");
                Eloquent::writeEloquentModelHelper($this, $this->files);
            } else {
                $this->error("The helper file could not be created at $filename");
            }
        }
    }
}