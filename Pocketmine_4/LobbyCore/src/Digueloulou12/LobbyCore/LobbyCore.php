<?php

namespace Digueloulou12\LobbyCore;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Commands\ChatCommand;
use Digueloulou12\LobbyCore\Commands\NpcCommand;
use Digueloulou12\LobbyCore\Entities\NpcEntity;
use Digueloulou12\LobbyCore\Events\BlockEvents;
use Digueloulou12\LobbyCore\Events\OtherEvents;
use Digueloulou12\LobbyCore\Events\PlayerEvents;
use Digueloulou12\LobbyCore\Tasks\SentencesTask;
use Digueloulou12\LobbyCore\Tasks\UpdateScoreboard;
use Digueloulou12\LobbyCore\Tasks\UpdateTask;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class LobbyCore extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        EntityFactory::getInstance()->register(NpcEntity::class, function (World $world, CompoundTag $nbt): NpcEntity {
            return new NpcEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["Npc"]);

        new RankAPI();

        foreach ([new PlayerEvents(), new BlockEvents(), new OtherEvents()] as $event) {
            $this->getServer()->getPluginManager()->registerEvents($event, $this);
        }

        $permission = Utils::getConfigValue("chatCommand")[2] ?? null;
        if (!is_null($permission)) PermissionManager::getInstance()->addPermission(new Permission($permission));
        $this->getServer()->getCommandMap()->register("LobbyCoreCommand", new ChatCommand(
            Utils::getConfigValue("chatCommand")[0] ?? "chat",
            Utils::getConfigValue("chatCommand")[1] ?? "",
            Utils::getConfigValue("chatCommandAliases") ?? [],
            $permission
        ));

        $permission = Utils::getConfigValue("npcCommand")[2] ?? null;
        if (!is_null($permission)) PermissionManager::getInstance()->addPermission(new Permission($permission));
        $this->getServer()->getCommandMap()->register("LobbyCoreCommandNpc", new NpcCommand(
            Utils::getConfigValue("npcCommand")[0] ?? "npc",
            Utils::getConfigValue("npcCommand")[1] ?? "",
            Utils::getConfigValue("npcCommandAliases") ?? [],
            $permission
        ));

        $this->getScheduler()->scheduleRepeatingTask(new SentencesTask(), 20 * Utils::getConfigValue("sentence_time"));
        $this->getScheduler()->scheduleRepeatingTask(new UpdateTask(), 20 * 300);
        $this->getScheduler()->scheduleRepeatingTask(new UpdateScoreboard(), 20 * Utils::getConfigValue("update_scoreboard"));
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}