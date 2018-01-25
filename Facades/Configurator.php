<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\AbstractFacade;

/**
 * The facade for @see \Rezonans\Core\Flow\Configurator
 * @method static \Rezonans\Core\Flow\Configurator loadEnv(string $path)
 * @method static \Rezonans\Core\Flow\Configurator readConfigDir(string $path)
 * @method static null|mixed env(string $var, $default = null)
 */
class Configurator extends AbstractFacade
{
    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get(\Rezonans\Core\Flow\Configurator::class);
    }
}