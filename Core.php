<?php declare(strict_types=1);

namespace Rezonans\Core;

use Closure;
use Illuminate\Container\Container;
use Monolog\Logger;
use Rezonans\Core\Contracts\ResponseInterface;
use Rezonans\Core\Contracts\TemplateInterface;
use Rezonans\Core\Facades\RouterStore;
use Rezonans\Core\Facades\ShutdownPromisePool;
use Rezonans\Core\Flow\ContainerManager;
use Rezonans\Core\Flow\PromiseManager;
use Rezonans\Core\Flow\ServiceRegistrator;
use Rezonans\Core\Flow\Path;
use Rezonans\Core\Flow\PromisePool;
use Rezonans\Core\Http\WpResponse;
use Rezonans\Core\Render\MustacheTemplate;
use Rezonans\Core\Render\View;
use Rezonans\Core\Facades\Core as CoreFacade;
use Rezonans\Core\Wordpress\WpProvider;

/**
 * Class Core
 * @package Core
 */
final class Core
{
    /** @var ContainerManager */
    protected $containerManager;

    /** @var Path */
    protected $path;

    /** @var string */
    protected $coreRoot = __DIR__;

    /** @var Logger */
    protected $logger = null;

    /**
     * App constructor. Bootstrap
     * @throws \Exception
     */
    public function __construct()
    {
        $this->containerManager = new ContainerManager();

        $this->containerManager->initInstructions(function (Container $container) {
            $this->initServiceContainer($container);
        });

        $this->containerManager->createContainer();

        CoreFacade::setFacadeRoot($this);
    }

    /**
     * Run the App
     * @param callable $applicationThick
     */
    public function run(callable $applicationThick)
    {
        try {
            /** @var PromiseManager $promiseManager */
            $promiseManager = $this->getContainerManager()
                ->getContainer()
                ->get(PromiseManager::class);

            $promiseManager->addPromise('shutdown', function () {
                ShutdownPromisePool::callAllPromises();
            });

            $applicationThick();

            RouterStore::makeBinding();

        } catch (\Throwable $e) {
            if (!is_null($this->logger)) {
                $this->logger->error($e->getMessage(), [
                    'stacktrace' => $e->getTrace(),
                ]);
            }
        } finally {
            ShutdownPromisePool::callAllPromises();
        }
    }

    /**
     * Accessor to ContainerManager
     * @return ContainerManager
     */
    public function getContainerManager(): ContainerManager
    {
        return $this->containerManager;
    }

    /**
     * Set path instance by App
     * @param string $appRoot
     */
    public function setPath(string $appRoot)
    {
        $this->path = new Path($appRoot, $this->coreRoot);

        $this->containerManager
            ->getContainer()
            ->instance(Path::class, $this->path);
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Accessor to environment vars
     * @param string $var
     * @param $default
     * @return null|mixed
     */
    public function env(string $var, $default = null)
    {
        $value = getenv($var) ?? null;

        if ($value === false) {
            return $default instanceof Closure ? $default() : $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (
            strlen($value) > 1
            && (0 === strpos($value, '"'))
            && (strlen($value) - 1 === strpos($value, '"'))
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Service container inti instructions
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    protected function initServiceContainer(Container $container)
    {
        $sr = new ServiceRegistrator($this->coreRoot, "Rezonans\\Core\\", $container);

        $sr->exclude(Path::class);

        $sr->prepareArguments(View::class,
            [
                TemplateInterface::class => MustacheTemplate::class,
                ResponseInterface::class => WpResponse::class
            ]
        );

        $sr->walkDirForServices('Flow');
        $sr->walkDirForServices('DataSource');
        $sr->walkDirForServices('Helpers');
        $sr->walkDirForServices('Http');
        $sr->walkDirForServices('Render');
        $sr->walkDirForServices('Wordpress');

        $container->singleton('router-store', \Rezonans\Core\Http\RouterStore::class);
        $container->instance('promise-pool.shutdown', new PromisePool());
    }
}
