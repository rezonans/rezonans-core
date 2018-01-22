<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\AbstractFacade;

/**
 * The facade for @see \Rezonans\Core\Render\Assets
 *
 * @method static $this registerStyle(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Rezonans\Core\Render\Assets registerFooterScript(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Rezonans\Core\Render\Assets registerHeaderScript(string $key, ?string $path = null, array $deps = [], ?string $ver = null)
 * @method static \Rezonans\Core\Render\Assets addVariableToScript(string $key, string $name, $value = null)
 */
class Assets extends AbstractFacade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get(\Rezonans\Core\Render\Assets::class);
    }
}