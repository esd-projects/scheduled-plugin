<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 16:44
 */

namespace GoSwoole\Plugins\Scheduled\ExampleClass;


use GoSwoole\BaseServer\Server\Beans\Request;
use GoSwoole\BaseServer\Server\Beans\Response;
use GoSwoole\BaseServer\Server\Beans\WebSocketFrame;
use GoSwoole\BaseServer\Server\Server;
use GoSwoole\BaseServer\Server\ServerPort;
use GoSwoole\Plugins\Scheduled\Beans\ScheduledTask;
use GoSwoole\Plugins\Scheduled\ScheduledPlugin;

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