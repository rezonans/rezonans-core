<?php declare(strict_types=1);

namespace Rezonans\Core\Tests\Unit\Render\Pug;

use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Contracts\TemplateInterface;
use Rezonans\Core\Flow\Path;
use Rezonans\Core\Http\RegularResponse;
use Rezonans\Core\Render\PugTemplate;
use Rezonans\Core\Render\View;
use Rezonans\Core\Tests\TestCase;

/**
 * Help test the pug
 * Required: @see TestCase
 * @method coreSetInstance($a, $b)
 * @method coreIoCPrepareArguments($a, $b)
 * @method assertEquals($a, $b)
 * @method assertNotEquals($a, $b)
 */
trait PugTestingHelperTrait
{
    protected function mocking()
    {
        $path = new class (__DIR__, __DIR__) extends Path
        {
            public function getTplPath(string $tail = ''): string
            {
                return __DIR__ . '/tpl';
            }
        };

        $this->coreSetInstance(Path::class, $path);

        $this->coreIoCPrepareArguments(View::class, [
            TemplateInterface::class => PugTemplate::class,
            ResponseInterface::class => RegularResponse::class,
        ]);
    }

    protected function assertEqualsTrimmed($expect, $actual)
    {
        $trimmedExpect = preg_replace('/\s/', '', $expect);
        $trimmedActual = preg_replace('/\s/', '', $actual);

        $this->assertEquals($trimmedExpect, $trimmedActual);
    }

    protected function assertNotEqualsTrimmed($expect, $actual)
    {
        $trimmedExpect = preg_replace('/\s/', '', $expect);
        $trimmedActual = preg_replace('/\s/', '', $actual);

        $this->assertNotEquals($trimmedExpect, $trimmedActual);
    }
}