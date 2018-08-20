<?php
/**
 * Created by PhpStorm.
 * User: huangchengwen
 * Date: 2018/7/3
 * Time: 10:40
 */

namespace App\Providers\IdeHelper;

use Barryvdh\LaravelIdeHelper\Generator as BaseGenerator;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Config;

class Generator extends BaseGenerator
{
    protected function getAliases()
    {
        // For Laravel, use the AliasLoader
        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            return AliasLoader::getInstance()->getAliases();
        }

        $facades = [
            'App' => 'Illuminate\Support\Facades\App',
            'Auth' => 'Illuminate\Support\Facades\Auth',
            'Bus' => 'Illuminate\Support\Facades\Bus',
            'DB' => 'Illuminate\Support\Facades\DB',
            'Cache' => 'Illuminate\Support\Facades\Cache',
            'Cookie' => 'Illuminate\Support\Facades\Cookie',
            'Crypt' => 'Illuminate\Support\Facades\Crypt',
            'Event' => 'Illuminate\Support\Facades\Event',
            'Hash' => 'Illuminate\Support\Facades\Hash',
            'Log' => 'Illuminate\Support\Facades\Log',
            'Mail' => 'Illuminate\Support\Facades\Mail',
            'Queue' => 'Illuminate\Support\Facades\Queue',
            'Request' => 'Illuminate\Support\Facades\Request',
            'Schema' => 'Illuminate\Support\Facades\Schema',
            'Session' => 'Illuminate\Support\Facades\Session',
            'Storage' => 'Illuminate\Support\Facades\Storage',
        ];

        $config_facades = Config::get('app.alias');
        $facades = array_merge($facades, $config_facades);
        $facades = array_merge($facades, $this->config->get('app.aliases', []));

        // Only return the ones that actually exist
        return array_filter($facades, function ($alias) {
            return class_exists($alias);
        });
    }
}