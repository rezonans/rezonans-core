<?php declare(strict_types=1);

namespace Rezonans\Core\Contracts;

use Rezonans\Core\Core;

/**
 * Implement it in your application
 */
interface AppInterface
{
    /**
     * Inject core into application
     * @param Core $core
     */
    public function handle(Core $core);
}