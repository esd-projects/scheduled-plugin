<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 15:05
 */

namespace GoSwoole\Plugins\Scheduled;


use GoSwoole\BaseServer\Plugins\Logger\GetLogger;
use GoSwoole\BaseServer\Server\Server;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;
use GoSwoole\Plugins\Scheduled\Event\ScheduledExecuteEvent;

class Scheduled
{
    use GetLogger;
    private $taskInstanceMap = [];

    public function __construct()
    {
        //监听任务事件的执行
        goWithContext(function () {
            $channel = Server::$instance->getEventDispatcher()->listen(ScheduledExecuteEvent::ScheduledExecuteEvent);
            while (true) {
                $event = $channel->pop();
                if ($event instanceof ScheduledExecuteEvent) {
                    goWithContext(function () use ($event) {
                        $this->execute($event->getTask());
                    });
                }
            }
        });
    }

    /**
     * 执行调度
     * @param ScheduledTask $scheduledTask
     */
    public function execute(ScheduledTask $scheduledTask)
    {
        $taskInstance = $this->taskInstanceMap[$scheduledTask->getName()] ?? null;
        if ($taskInstance == null) {
            $className = $scheduledTask->getClassName();
            $taskInstance = new $className();
            $this->taskInstanceMap[$scheduledTask->getName()] = $taskInstance;
        }
        call_user_func([$taskInstance, $scheduledTask->getFunctionName()]);
        $this->debug("执行{$scheduledTask->getName()}任务");
    }
}