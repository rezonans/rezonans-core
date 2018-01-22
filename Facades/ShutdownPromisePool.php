<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\AbstractFacade;
use Rezonans\Core\Flow\PromisePool;

/**
 * The facade for the Instance (as service) of @see PromisePool
 *
 * @method static addAnonymousPromise(callable $promise, ?int $priority = null)
 * @method static callAllPromises()
 */
class ShutdownPromisePool extends AbstractFacade
{
    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get('promise-pool.shutdown');
    }
}