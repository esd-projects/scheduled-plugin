<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 14:37
 */

namespace GoSwoole\Plugins\Scheduled;

use GoSwoole\BaseServer\Plugins\Logger\GetLogger;
use GoSwoole\BaseServer\Server\Context;
use GoSwoole\BaseServer\Server\Plugin\AbstractPlugin;
use GoSwoole\BaseServer\Server\Server;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;
use GoSwoole\Plugins\Scheduled\Event\ScheduledAddEvent;
use GoSwoole\Plugins\Scheduled\Event\ScheduledExecuteEvent;

class ScheduledPlugin extends AbstractPlugin
{
    use GetLogger;
    const processName = "helper";
    const processGroupName = "HelperGroup";

    /**
     * @var ScheduledConfig
     */
    private $scheduledConfig;

    /**
     * 进程任务调度次数
     * @var array
     */
    private $processScheduledCount = [];

    /**
     * ScheduledPlugin constructor.
     * @param ScheduledConfig|null $scheduledConfig
     * @throws \GoSwoole\BaseServer\Server\Exception\ConfigException
     */
    public function __construct(ScheduledConfig $scheduledConfig = null)
    {
        parent::__construct();
        if ($scheduledConfig == null) {
            $scheduledConfig = new ScheduledConfig();
        }
        $this->scheduledConfig = $scheduledConfig;
    }

    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Scheduled";
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @return mixed
     * @throws \GoSwoole\BaseServer\Server\Exception\ConfigException
     */
    public function beforeServerStart(Context $context)
    {
        //添加一个helper进程
        $context->getServer()->addProcess(self::processName, HelperScheduledProcess::class, self::processGroupName);
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @return mixed
     */
    public function beforeProcessStart(Context $context)
    {
        new Scheduled();
        //Help进程
        if ($context->getServer()->getProcessManager()->getCurrentProcess()->getProcessName() === self::processName) {
            //初始化计数器
            foreach (Server::$instance->getProcessManager()->getProcesses() as $process) {
                $this->processScheduledCount[$process->getProcessId()] = 0;
            }
            //监听动态添加的任务事件
            goWithContext(function () {
                $channel = Server::$instance->getEventDispatcher()->listen(ScheduledAddEvent::ScheduledAddEvent);
                while (true) {
                    $event = $channel->pop();
                    if ($event instanceof ScheduledAddEvent) {
                        $this->scheduledConfig->addScheduled($event->getTask());
                    }
                }
            });
            //添加定时器调度
            addTimerTick($this->scheduledConfig->getMinIntervalTime(), function () {
                foreach ($this->scheduledConfig->getSchedulerTasks() as $scheduledTask) {
                    print_r($scheduledTask->getCron()->getNextRunDate());
                    var_dump($scheduledTask->getCron()->isDue());
                    if ($scheduledTask->getCron()->isDue()) {
                        //按执行次数从小到大排列
                        asort($this->processScheduledCount);
                        $process = null;
                        foreach ($this->processScheduledCount as $id => $value) {
                            if ($scheduledTask->getProcessGroup() == ScheduledTask::ProcessGroupAll) {
                                $process = Server::$instance->getProcessManager()->getProcessFromId($id);
                                break;
                            } else {
                                if (Server::$instance->getProcessManager()->getProcessFromId($id)->getGroupName() == $scheduledTask->getProcessGroup()) {
                                    $process = Server::$instance->getProcessManager()->getProcessFromId($id);
                                    break;
                                }
                            }
                        }
                        if ($process != null) {
                            $this->processScheduledCount[$process->getProcessId()]++;
                            Server::$instance->getEventDispatcher()->dispatchProcessEvent(new ScheduledExecuteEvent($scheduledTask), $process);
                        } else {
                            $this->warn("{$scheduledTask->getName()}任务没有找到可以调度的进程");
                        }
                    }
                }
            });
        }
        $this->ready();
    }

    /**
     * @return ScheduledConfig
     */
    public function getScheduledConfig(): ScheduledConfig
    {
        return $this->scheduledConfig;
    }

    /**
     * @param ScheduledConfig $scheduledConfig
     */
    public function setScheduledConfig(ScheduledConfig $scheduledConfig): void
    {
        $this->scheduledConfig = $scheduledConfig;
    }
}