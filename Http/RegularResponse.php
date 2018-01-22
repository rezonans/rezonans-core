<?php declare(strict_types=1);

namespace Rezonans\Core\Http;

use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Facades\ShutdownPromisePool;

/**
 * The regular html/text response
 */
class RegularResponse extends BaseResponse implements ResponseInterface
{
    public function send()
    {
        $result = parent::send();
        ShutdownPromisePool::callAllPromises();
        return $result;
    }
}