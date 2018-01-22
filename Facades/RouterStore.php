<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\ActionInterface;
use Rezonans\Core\Contracts\RouteConditionInterface;
use Rezonans\Core\Contracts\AbstractFacade;

/**
 * The facade for @see \Rezonans\Core\Http\RouterStore
 *
 * @method static add(RouteConditionInterface $condition, ActionInterface $action, ?string $key = null)
 * @method static bool removeByKey(string $key)
 * @method static makeBinding()
 */
class RouterStore extends AbstractFacade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get('router-store');
    }
}