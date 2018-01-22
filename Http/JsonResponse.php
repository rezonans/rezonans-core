<?php declare(strict_types=1);

namespace Rezonans\Core\Http;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Facades\ShutdownPromisePool;

/**
 * The response in JSON
 */
class JsonResponse extends BaseJsonResponse implements ResponseInterface
{
    public function send()
    {
        $result = parent::send();
        ShutdownPromisePool::callAllPromises();
        return $result;
    }
}