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

    public function __construct(ScheduledTask $data)
    {
        parent::__construct(self::ScheduledRemoveEvent, $data);
    }

    /**
     * @return ScheduledTask
     */
    public function getTask(): ScheduledTask
    {
        return $this->getData();
    }
}