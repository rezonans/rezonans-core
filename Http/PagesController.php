<?php declare(strict_types=1);

namespace Rezonans\Core\Http;

use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Facades\Configurator;
use Rezonans\Core\Facades\Core;

/**
 * Inherit it in your application when will build controller for wordpress site pages
 */
class PagesController extends AbstractResponder
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function makeResponse(int $status, $content, ?\Throwable $e = null): ResponseInterface
    {
        $exceptionMarker = (2 != intval($status / 100));

        if (!is_string($content)) {
            $content = (string)$content;
        }

        if ($exceptionMarker && ('testing' === Configurator::env('PROJECT_ENVIRONMENT'))) {

            $logData = [$content, $status];
            is_null($e) ?: $logData[] = $e->getTraceAsString();
            Core::get('Logger')->info($logData);

            return new WpResponse($content ?? '', $status);
        }

        if($exceptionMarker) {
            $content = '';
        }

        return new WpResponse($content ?? '', $status);
    }
}