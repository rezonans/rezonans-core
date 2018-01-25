<?php declare(strict_types=1);

namespace Rezonans\Core\Tests\Unit;

use Illuminate\Container\Container;
use Monolog\Logger;
use Rezonans\Core\Contracts\AbstractApp;
use Rezonans\Core\Facades\Core;
use Rezonans\Core\Flow\PromiseManager;
use Rezonans\Core\Tests\TestCase;
use Rezonans\Core\Wordpress\WpProvider;

class AppTest extends TestCase
{
    /**
     * @test
     */
    public function emulateRun()
    {
        //Arrange
        $phpMockWpAddAction = <<<'CODE'
            function add_action()
            {
                ;
            }
CODE;
        if(!function_exists('\add_action')) {
            eval($phpMockWpAddAction);
        }


        $pmMock = new class(new WpProvider()) extends PromiseManager
        {
            public $promise = [];

            public function addPromise(string $name, callable $promise, ?int $priority = null)
            {
                $this->promise[] = $name;
            }
        };

        (new class ('') extends AbstractApp
        {
            public function __construct(string $appRoot)
            {
                parent::__construct($appRoot);
            }

            public function handle(\Rezonans\Core\Core $core)
            {
                parent::handle($core);
            }

            public function run()
            {   /** @var PromiseManager $pm */
                $pm = Core::get(PromiseManager::class);
                $pm->addPromise('GoingTest', 'assert');
            }
        })->beforeRun(function (\Rezonans\Core\Core $core) use ($pmMock) {

            $core->setLogger(new class('test') extends Logger
            {
                public function error($message, array $context = array())
                {
                    dump([$message]);
                }
            });

            /** @var Container $cb */
            $cb = $this->core->getContainerManager()->getContainer();
            $cb->instance(PromiseManager::class, $pmMock);

        })->handle($this->core);

        $this->assertSame(['shutdown', 'GoingTest'], $pmMock->promise);
    }
}