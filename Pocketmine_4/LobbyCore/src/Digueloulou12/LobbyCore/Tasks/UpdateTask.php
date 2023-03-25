<?php

namespace Digueloulou12\LobbyCore\Tasks;

use Digueloulou12\LobbyCore\API\ServerInfo;
use Digueloulou12\LobbyCore\Entities\NpcEntity;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class UpdateTask extends Task
{
    public static int $connect = 0;
    public static int $maxConnect = 0;

    public function onRun(): void
    {
        $connect = count(Server::getInstance()->getOnlinePlayers());
        $maxConnect = Server::getInstance()->getMaxPlayers();
        foreach (Utils::getConfigValue("servers") as $server => $value) {
            $connect += (new ServerInfo($value[0], $value[1] ?? 19132))->getOnlinePlayers();
            $maxConnect += (new ServerInfo($value[0], $value[1] ?? 19132))->getMaxPlayers();
        }
        self::$connect = $connect;
        self::$maxConnect = $maxConnect;
        Server::getInstance()->getQueryInformation()->setPlayerCount($connect);
        Server::getInstance()->getQueryInformation()->setMaxPlayerCount($maxConnect);

        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                if ($entity instanceof NpcEntity) {
                    $entity->updateNameTag();
                }
            }
        }
    }
}