<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 16:44
 */

namespace ESD\Plugins\Scheduled\ExampleClass;


use ESD\BaseServer\Server\Beans\Request;
use ESD\BaseServer\Server\Beans\Response;
use ESD\BaseServer\Server\Beans\WebSocketFrame;
use ESD\BaseServer\Server\Server;
use ESD\BaseServer\Server\ServerPort;
use ESD\Plugins\Scheduled\Beans\ScheduledTask;
use ESD\Plugins\Scheduled\ScheduledPlugin;

class TestPort extends ServerPort
{

    public function onTcpConnect(int $fd, int $reactorId)
    {
        // TODO: Implement onTcpConnect() method.
    }

    public function onTcpClose(int $fd, int $reactorId)
    {
        // TODO: Implement onTcpClose() method.
    }

    public function onTcpReceive(int $fd, int $reactorId, string $data)
    {
        // TODO: Implement onTcpReceive() method.
    }

    public function onUdpPacket(string $data, array $client_info)
    {
        // TODO: Implement onUdpPacket() method.
    }

    public function onHttpRequest(Request $request, Response $response)
    {
        $scheduledTask = new ScheduledTask("DynamicAdd", "* * * * * *", TestScheduledTask::class, "dynamic");
        if($request->getServer(Request::SERVER_REQUEST_URI)=="/add") {
            $scheduledPlugin = Server::$instance->getPlugManager()->getPlug(ScheduledPlugin::class);
            if ($scheduledPlugin instanceof ScheduledPlugin) {
                $scheduledPlugin->getScheduledConfig()->addScheduled(
                    new ScheduledTask("DynamicAdd", "* * * * * *", TestScheduledTask::class, "dynamic")
                );
            }
            $response->end("add");
        }else{
            $scheduledPlugin = Server::$instance->getPlugManager()->getPlug(ScheduledPlugin::class);
            if ($scheduledPlugin instanceof ScheduledPlugin) {
                $scheduledPlugin->getScheduledConfig()->removeScheduled($scheduledTask->getName());
            }
            $response->end("remove");
        }
    }

    public function onWsMessage(WebSocketFrame $frame)
    {
        // TODO: Implement onWsMessage() method.
    }

    public function onWsOpen(Request $request)
    {
        // TODO: Implement onWsOpen() method.
    }

    public function onWsPassCustomHandshake(Request $request): bool
    {
        // TODO: Implement onWsPassCustomHandshake() method.
    }
}