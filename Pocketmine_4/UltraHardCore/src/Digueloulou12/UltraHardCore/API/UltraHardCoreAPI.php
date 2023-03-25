<?php

namespace Digueloulou12\UltraHardCore\API;

use Digueloulou12\UltraHardCore\Tasks\AirDropTask;
use Digueloulou12\UltraHardCore\Tasks\UltraHardCoreTask;
use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use JetBrains\PhpStorm\Pure;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\Position;

class UltraHardCoreAPI
{
    private ?AirDropTask $airDropTask = null;
    /** @var Player[] $players */
    private array $players = [];
    private UltraHardCore $core;
    private bool $pvp = false;
    private bool $waiting = false;
    private bool $game = false;
    private Task $task;

    const WAITING = "waiting";
    const MINE = "mine";
    const PVP = "pvp";

    #[Pure] public function __construct()
    {
        $this->core = UltraHardCore::getInstance();
    }

    public function startGame(): void
    {
        $this->game = true;
        $this->waiting = true;

        $this->task = new UltraHardCoreTask(self::WAITING);
        $this->core->getScheduler()->scheduleRepeatingTask($this->task, 20);
        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("server_start"));
    }

    public function stopGame(bool $finish = false): void
    {
        $this->game = false;
        $this->waiting = false;
        $this->pvp = false;

        if (!is_null($this->airDropTask)) $this->airDropTask->getHandler()->cancel();

        $this->task->getHandler()->cancel();

        foreach ($this->getAllPlayers() as $player) {
            $player->teleport($player->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        }

        if ($finish) {
            $winner = $this->getAllPlayers()[0]->getName();
            Server::getInstance()->broadcastMessage(Utils::getConfigReplace("finish_game", "{winner}", $winner));
        } else $this->sendMessage(Utils::getConfigReplace("game_stop"));

        $this->players = [];
    }

    public function stopJoin(): void
    {
        $this->waiting = false;

        $task = new AirDropTask();
        $this->airDropTask = $task;
        UltraHardCore::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20 * 60 * Utils::getConfigValue("time_airdrop"));

        if (!$this->core->getServer()->getWorldManager()->isWorldLoaded(Utils::getConfigValue("game_world"))) {
            $this->core->getServer()->getWorldManager()->loadWorld(Utils::getConfigValue("game_world"));
        }

        foreach ($this->getAllPlayers() as $player) {
            $x = mt_rand(Utils::getConfigValue("random_x_min"), Utils::getConfigValue("random_x_max"));
            $z = mt_rand(Utils::getConfigValue("random_z_min"), Utils::getConfigValue("random_z_max"));
            $world = $this->core->getServer()->getWorldManager()->getWorldByName(Utils::getConfigValue("game_world"));
            $player->teleport($world->getSafeSpawn(new Position($x, 100, $z, $world)));

            foreach (Utils::getConfigValue("items") as $item) {
                $player->getInventory()->addItem(ItemFactory::getInstance()->get($item[0], $item[1], $item[2]));
            }
        }
    }

    public function isGame(): bool
    {
        return $this->game;
    }

    public function canJoin(): bool
    {
        return $this->waiting and count($this->getAllPlayers()) <= Utils::getConfigValue("max_player");
    }

    public function getAllPlayers(): array
    {
        return $this->players;
    }

    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;

        $pos = Utils::getConfigValue("waiting_pos");
        $player->teleport(new Position($pos[0], $pos[1], $pos[2], $player->getServer()->getWorldManager()->getWorldByName($pos[3])));
    }

    public function removePlayer(Player $player, $kill = false): void
    {
        unset($this->players[array_search($player, $this->players)]);
        $this->sendMessage(Utils::getConfigReplace("player_leave", "{player}", $player->getName()), true);
        $this->checkEnd($kill);
    }

    #[Pure] public function isInGame(Player $player): bool
    {
        return in_array($player, $this->getAllPlayers());
    }

    public function sendMessage(string $message, bool $popup = false): void
    {
        foreach ($this->players as $player) $popup ? $player->sendPopup($message) : $player->sendMessage($message);
    }

    public function setPvp(bool $pvp): void
    {
        $this->pvp = $pvp;
    }

    public function canPvP(): bool
    {
        return $this->pvp;
    }

    public function checkEnd(bool $kill = false): void
    {
        if ($this->isGame()) {
            if (count($this->getAllPlayers()) === 1) {
                $this->stopGame($kill);
            }
        }
    }
}