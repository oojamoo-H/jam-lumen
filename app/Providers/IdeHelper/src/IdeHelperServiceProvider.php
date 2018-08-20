<?php

namespace App\Providers\IdeHelper;

use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider as IdeProvider;

class IdeHelperServiceProvider extends IdeProvider
{

    public function register()
    {
        $configPath = __DIR__ . '/../config/ide-helper.php';
        $this->mergeConfigFrom($configPath, 'ide-helper');
        $localViewFactory = $this->createLocalViewFactory();

        $this->app->singleton(
            'command.ide-helper.generate',
            function ($app) use ($localViewFactory) {
                return new GeneratorCommand($app['config'], $app['files'], $localViewFactory);
            }
        );

        $this->commands(
            'command.ide-helper.generate'
        );
    }

    /**
     * @return Factory
     */
    private function createLocalViewFactory()
    {
        $resolver = new EngineResolver();
        $resolver->register('php', function () {
            return new PhpEngine();
        });
        $finder = new FileViewFinder($this->app['files'], [__DIR__ . '/../resources/views']);
        $factory = new Factory($resolver, $finder, $this->app['events']);
        $factory->addExtension('php', 'php');

        return $factory;
    }
}