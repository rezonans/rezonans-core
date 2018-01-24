<?php declare(strict_types=1);

namespace Rezonans\Core\Contracts;

use Symfony\Component\Dotenv\Dotenv;
use Rezonans\Core\Core;
use Rezonans\Core\Facades\Path;

/**
 * Use it as the base for you application
 */
abstract class AbstractApp implements AppInterface
{
    /** @var string */
    protected $appRoot;

    /** @var Core */
    protected $core;

    /** @var ?callable */
    protected $beforeRun = null;

    public function __construct(string $appRoot)
    {
        $this->appRoot = $appRoot;
    }

    /**
     * Getting the core to handle it
     * @param Core $core
     */
    public function handle(Core $core)
    {
        $this->core = $core;

        if(!is_null($this->beforeRun)) {
            call_user_func_array($this->beforeRun, [$this->core]);
        }

        $this->core->run([$this, 'run']);
    }

    /**
     * Will be called after setup but before run
     * @param callable $callback
     * @return AppInterface
     */
    public function beforeRun(callable $callback): AppInterface
    {
        $this->beforeRun = $callback;
        return $this;
    }

    /**
     * Waiting for the core who call it at the time
     */
    abstract public function run();
}