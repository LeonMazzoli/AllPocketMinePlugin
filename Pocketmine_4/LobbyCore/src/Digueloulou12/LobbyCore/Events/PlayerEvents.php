<?php

namespace Digueloulou12\LobbyCore\Events;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\API\ScoreboardAPI;
use Digueloulou12\LobbyCore\Commands\ChatCommand;
use Digueloulou12\LobbyCore\Forms\ServersForm;
use Digueloulou12\LobbyCore\Utils\Utils;
use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\BlazeRod;
use pocketmine\item\Compass;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerEvents implements Listener
{
    public static array $players = [];

    /**
     * @throws JsonException
     */
    public function onJoin(PlayerJoinEvent $event): void
    {
        $event->setJoinMessage(Utils::getConfigReplace("message_join", "{player}", $event->getPlayer()->getName()));
        $event->getPlayer()->setGamemode(GameMode::ADVENTURE());

        RankAPI::createPlayer($event->getPlayer());
        RankAPI::setNameTag($event->getPlayer());
        RankAPI::initPermission($event->getPlayer());

        ScoreboardAPI::sendScoreboard($event->getPlayer());

        $event->getPlayer()->getInventory()->clearAll();
        $event->getPlayer()->getArmorInventory()->clearAll();
        $event->getPlayer()->getInventory()->setItem(Utils::getConfigValue("enderpearl"), VanillaItems::ENDER_PEARL());
        $event->getPlayer()->getInventory()->setItem(Utils::getConfigValue("blaze_rod"), VanillaItems::BLAZE_ROD());
        $event->getPlayer()->getInventory()->setItem(Utils::getConfigReplace("compass"), VanillaItems::COMPASS());

        foreach (self::$players as $player) {
            $player = Server::getInstance()->getPlayerExact($player);
            if ($player instanceof Player) {
                $player->hidePlayer($event->getPlayer());
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $event->setQuitMessage(Utils::getConfigReplace("message_quit", "{player}", $event->getPlayer()->getName()));
    }

    public function onChat(PlayerChatEvent $event): void
    {
        if (ChatCommand::$chat) {
            if (!(Server::getInstance()->isOp($event->getPlayer()->getName())) and !($event->getPlayer()->hasPermission(Utils::getConfigReplace("chatCommandPermissionByPass")))) {
                $event->cancel();
                return;
            }
        }

        $rank = RankAPI::getRank($event->getPlayer());
        $color = RankAPI::getRankColor($rank);

        $event->setFormat(Utils::getConfigReplace("chat_format", ["{rank}", "{name}", "{msg}", "{color}"], [$rank, $event->getPlayer()->getDisplayName(), $event->getMessage(), $color]));
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        $item = $event->getItem();
        if ($item instanceof Compass) {
            $event->getPlayer()->sendForm(new ServersForm());
            return;
        }

        if ($item instanceof BlazeRod) {
            $player = $event->getPlayer();
            if (in_array($player->getName(), self::$players)) {
                unset(self::$players[array_search($player->getName(), self::$players)]);
                foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                    $player->showPlayer($onlinePlayer);
                }
                $player->sendMessage(Utils::getConfigReplace("blazeOff"));
            } else {
                self::$players[] = $player->getName();
                foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                    $player->hidePlayer($onlinePlayer);
                }
                $player->sendMessage(Utils::getConfigReplace("blazeOn"));
            }
        }
    }
}