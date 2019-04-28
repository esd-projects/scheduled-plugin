<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 14:39
 */

namespace GoSwoole\Plugins\Scheduled;


use GoSwoole\BaseServer\Server\Exception\ConfigException;
use GoSwoole\BaseServer\Server\Server;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;
use GoSwoole\Plugins\Scheduled\Event\ScheduledAddEvent;

class ScheduledConfig
{
    /**
     * 最小间隔时间
     * @var int
     */
    private $minIntervalTime;
    /**
     * @var ScheduledTask[]
     */
    private $schedulerTasks = [];

    /**
     * ScheduledConfig constructor.
     * @param int $minIntervalTime
     * @throws ConfigException
     */
    public function __construct($minIntervalTime = 1000)
    {
        $this->minIntervalTime = $minIntervalTime;
        if($minIntervalTime<1000){
            throw new ConfigException("定时调度任务的最小时间单位为1s");
        }
    }

    /**
     * 添加调度
     * @param ScheduledTask $scheduledTask
     */
    public function addScheduled(ScheduledTask $scheduledTask)
    {
        if (!Server::$isStart) {
            //服务没启动可以直接添加
            $this->schedulerTasks[$scheduledTask->getName()] = $scheduledTask;
        } else {
            //服务启动了这里需要动态添加，借助于Event
            Server::$instance->getEventDispatcher()->dispatchProcessEvent(
                new ScheduledAddEvent($scheduledTask),
                Server::$instance->getProcessManager()->getProcessFromName(ScheduledPlugin::processName)
            );
        }
    }

    /**
     * @return int
     */
    public function getMinIntervalTime(): int
    {
        return $this->minIntervalTime;
    }

    /**
     * @return ScheduledTask[]
     */
    public function getSchedulerTasks(): array
    {
        return $this->schedulerTasks;
    }
}