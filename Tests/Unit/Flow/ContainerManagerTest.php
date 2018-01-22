<?php declare(strict_types=1);

namespace Rezonans\Core\Tests\Unit\Flow;

use Illuminate\Container\Container;
use Rezonans\Core\Flow\ContainerManager;
use Rezonans\Core\Tests\TestCase;

class ContainerManagerTest extends TestCase
{
    public function tearDown()
    {
        //stub
    }

    /**
     * @test
     */
    public function testInit()
    {
        /** @var ContainerManager $cm */
        $cm = $this->core->getContainerManager();

        $counter = 0;
        $cm->initInstructions(function (Container $container) use (&$counter){
            $this->assertTrue(true);
            $counter++;
        });

        $cm->createContainer();

        $this->assertSame(1, $counter);
    }
}