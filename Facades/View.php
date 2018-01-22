<?php declare(strict_types=1);

namespace Rezonans\Core\Facades;

use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Contracts\AbstractFacade;
use Rezonans\Core\Http\RegularResponse;

/**
 * The facade for @see \Rezonans\Core\Render\View
 *
 * @method static ResponseInterface display(string $key, array $data, int $status = RegularResponse::HTTP_OK)
 */
class View extends AbstractFacade
{

    /**
     * Return the facade root object
     * @return mixed
     * @throws \Exception
     * @throws \Error
     */
    public static function getFacadeRoot()
    {
        return Core::get(\Rezonans\Core\Render\View::class);
    }
}