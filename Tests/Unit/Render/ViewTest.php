<?php declare(strict_types=1);

namespace Rezonans\Core\Tests\Unit;

use Symfony\Component\DependencyInjection\Reference;
use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Contracts\TemplateInterface;
use Rezonans\Core\Http\RegularResponse;
use Rezonans\Core\Render\View;
use Rezonans\Core\Tests\TestCase;

/**
 * @see View
 */
class ViewTest extends TestCase
{
    /**
     * @test
     */
    public function display()
    {
        //Arrange
        $this->mockingUp();

        /** @var View $view */
        $view = $this->coreGet(View::class);

        $key = 'key';
        $data = ['data', 'another data'];
        $expect = '#TEST_TSET#' . $key . implode('|', $data);

        //Act
        $result = $view->display($key, $data);

        //Assert
        /** @var object $result */
        $this->assertSame($expect, $result->content);
        $this->assertSame(RegularResponse::HTTP_OK, $result->code);
        $this->assertFalse($result->sent);
    }

    /**
     * @test
     */
    public function useDefaultResponse()
    {
        //Arrange
        ['templateStrategyMock' => $templateStrategyMock] = $this->mockingUp();

        $this->coreSetInstance(RegularResponse::class, new class() implements ResponseInterface
        {
            public $sent = false;
            public $code = false;
            public $content = false;

            public function send()
            {
                $this->sent = true;
            }

            public function setStatusCode(int $code, $text = null): ResponseInterface
            {
                $this->code = $code + 1;
                return $this;
            }

            public function setContent($content): ResponseInterface
            {
                $this->content = $content . '>REGULAR';
                return $this;
            }
        });

        $view = new View($templateStrategyMock, null);

        $key = 'key';
        $data = ['data', 'another data'];
        $expect = '#TEST_TSET#' . $key . implode('|', $data) . '>REGULAR';

        //Act
        $result = $view->display($key, $data);

        //Assert
        /** @var object $result */
        $this->assertSame($expect, $result->content);
        $this->assertSame(RegularResponse::HTTP_OK + 1, $result->code);
        $this->assertFalse($result->sent);
    }

    /**
     * Mocking DI for the View
     * @return array
     */
    protected function mockingUp(): array
    {
        $templateStrategyMock = new class () implements TemplateInterface
        {
            public function render(string $key, array $data = []): string
            {
                return '#TEST_TSET#' . $key . implode('|', $data);
            }
        };

        $responseInterfaceMock = new class () implements ResponseInterface
        {
            public $sent = false;
            public $code = false;
            public $content = false;

            public function send()
            {
                $this->sent = true;
            }

            public function setStatusCode(int $code, $text = null): ResponseInterface
            {
                $this->code = $code;
                return $this;
            }

            public function setContent($content): ResponseInterface
            {
                $this->content = $content;
                return $this;
            }
        };

        $this->coreSetInstance(View::class, new View($templateStrategyMock, $responseInterfaceMock));

        return compact('templateStrategyMock', 'responseInterfaceMock');
    }
}