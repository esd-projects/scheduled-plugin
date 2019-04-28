<?php

use GoSwoole\BaseServer\ExampleClass\Server\DefaultServer;
use GoSwoole\BaseServer\Server\Config\PortConfig;
use GoSwoole\BaseServer\Server\Config\ServerConfig;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;
use GoSwoole\Plugins\Scheduled\ExampleClass\TestScheduledTask;
use GoSwoole\Plugins\Scheduled\ScheduledConfig;
use GoSwoole\Plugins\Scheduled\ScheduledPlugin;

require __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Shanghai');
//----多端口配置----
$httpPortConfig = new PortConfig();
$httpPortConfig->setHost("0.0.0.0");
$httpPortConfig->setPort(8080);
$httpPortConfig->setSockType(PortConfig::SWOOLE_SOCK_TCP);
$httpPortConfig->setOpenHttpProtocol(true);

//---服务器配置---
$serverConfig = new ServerConfig();
$serverConfig->setWorkerNum(1);
$serverConfig->setRootDir(__DIR__ . "/../");

$server = new DefaultServer($serverConfig);
//添加端口
$server->addPort("http", $httpPortConfig);
//添加插件
$scheduledConfig = new ScheduledConfig();
//添加调度任务
//- - -    -    -    -    -
//* *    *    *    *    *
//| |    |    |    |    |
//| |    |    |    |    |
//| |    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
//| |    |    |    +---------- month (1 - 12)
//| |    |    +--------------- day of month (1 - 31)
//| |    +-------------------- hour (0 - 23)
//| +------------------------- min (0 - 59)
//+--------------------------- sec (0 - 59)
$scheduledConfig->addScheduled(new ScheduledTask("test","* * * * * *",TestScheduledTask::class,"test"));
$server->getPlugManager()->addPlug(new ScheduledPlugin($scheduledConfig));
$server->addProcess("test1");
//配置
$server->configure();
//configure后可以获取实例
$httpPort = $server->getPortManager()->getPortFromName("http");
//启动
$server->start();
