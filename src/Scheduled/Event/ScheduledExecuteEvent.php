<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 14:46
 */

namespace GoSwoole\Plugins\Scheduled\Event;

use GoSwoole\BaseServer\Plugins\Event\Event;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;

class ScheduledExecuteEvent extends Event
{
    const ScheduledExecuteEvent = "ScheduledExecuteEvent";

    public function __construct(ScheduledTask $data)
    {
        parent::__construct(self::ScheduledExecuteEvent, $data);
    }

    /**
     * @return ScheduledTask
     */
    public function getTask(): ScheduledTask
    {
        return $this->getData();
    }
}