<?php

use ESD\Core\Server\Config\PortConfig;
use ESD\Core\Server\Config\ServerConfig;
use ESD\Plugins\Scheduled\Beans\ScheduledTask;
use ESD\Plugins\Scheduled\ExampleClass\TestPort;
use ESD\Plugins\Scheduled\ExampleClass\TestScheduledTask;
use ESD\Plugins\Scheduled\ScheduledConfig;
use ESD\Plugins\Scheduled\ScheduledPlugin;
use ESD\Server\Co\ExampleClass\DefaultServer;

require __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Shanghai');
//----多端口配置----
$httpPortConfig = new PortConfig();
$httpPortConfig->setHost("0.0.0.0");
$httpPortConfig->setPort(8081);
$httpPortConfig->setSockType(PortConfig::SWOOLE_SOCK_TCP);
$httpPortConfig->setOpenHttpProtocol(true);

//---服务器配置---
$serverConfig = new ServerConfig();
$serverConfig->setWorkerNum(1);
$serverConfig->setRootDir(__DIR__ . "/../");

$server = new DefaultServer($serverConfig);
//添加端口
$server->addPort("http", $httpPortConfig,TestPort::class);
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
$scheduledConfig->addScheduled(new ScheduledTask("test","0 * * * * *",TestScheduledTask::class,"test"));
$server->getPlugManager()->addPlug(new ScheduledPlugin($scheduledConfig));
$server->addProcess("test1");
//配置
$server->configure();
//configure后可以获取实例
$httpPort = $server->getPortManager()->getPortFromName("http");
//启动
$server->start();
