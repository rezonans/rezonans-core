<?php declare(strict_types=1);

namespace Rezonans\Core\Http\Drops;

use Rezonans\Core\Contracts\ActionInterface;
use Rezonans\Core\Contracts\RouteConditionInterface;

/**
 * The condition for wordpress ajax callbacks
 * TODO: Implement it
 */
class WpAjaxCondition implements RouteConditionInterface
{

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function bindWithAction(ActionInterface $action)
    {
        // TODO: Implement bindWithAction() method.
        throw new \Exception("Not implementded");
    }
}