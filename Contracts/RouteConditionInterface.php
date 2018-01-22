<?php declare(strict_types=1);

namespace Rezonans\Core\Contracts;
use Rezonans\Core\Http\RouterStore;

/**
 * Common interface for route conditions who participates in @see RouterStore::makeBinding()
 */
interface RouteConditionInterface
{
    public function bindWithAction(ActionInterface $action);
}