<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 14:46
 */

namespace GoSwoole\Plugins\Scheduled\Event;

use GoSwoole\BaseServer\Plugins\Event\Event;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;

class ScheduledRemoveEvent extends Event
{
    const ScheduledRemoveEvent = "ScheduledRemoveEvent";

    public function __construct(string $scheduledTaskName)
    {
        parent::__construct(self::ScheduledRemoveEvent, $scheduledTaskName);
    }

    /**
     * @return ScheduledTask
     */
    public function getTaskName(): string
    {
        return $this->getData();
    }
}