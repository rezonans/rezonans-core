<?php declare(strict_types=1);

namespace Rezonans\Core\Http;

use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Facades\Configurator;
use Rezonans\Core\Facades\Core;

/**
 * Inherit it in your application when will build REST API controller
 */
class ApiController extends AbstractResponder
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function makeResponse(int $status, $content, ?\Throwable $e = null): ResponseInterface
    {
        $exceptionMarker = (2 != intval($status / 100));

        if ($exceptionMarker && ('testing' === Configurator::env('PROJECT_ENVIRONMENT'))) {

            $logData = [$content, $status];
            is_null($e) ?: $logData[] = $e->getTraceAsString();
            Core::get('Logger')->info($logData);

            if (!is_array($content)) {
                $content = ['message' => $content];
            }

            return new JsonResponse($content, $status);
        }

        if($exceptionMarker) {
            $content = [];

        } elseif (!is_array($content)) {
            if (is_object($content)) {
                $content = get_object_vars($content);
            } else {
                $content = ['data' => $content];
            }
        }

        return new JsonResponse($content, $status);
    }
}