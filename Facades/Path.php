<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\AbstractFacade;

/**
 * The facade for @see \Rezonans\Core\Flow\Path
 *
 * @method static string getProjectRoot(string $tail = '')
 * @method static string getConfigPath(string $tail = '')
 * @method static string getWpPath(string $tail = '')
 * @method static string getCorePath(string $tail = '')
 * @method static string getAppPath(string $tail = '')
 * @method static string getTplPath(string $tail = '')
 * @method static string getSrcPath(string $tail = '')
 * @method static string getCurrentUrl()
 * @method static string getWpThemeUri(string $tail = '')
 * @method static string getCssUri(string $tail = '')
 * @method static string getJsUri(string $tail = '')
 * @method static string getImagesUri(string $tail = '')
 * @method static string getFontsUri(string $tail = '')
 */
class Path extends AbstractFacade
{
    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get(\Rezonans\Core\Flow\Path::class);
    }
}