<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class CommandDuel extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", MainDuel::$config->get("command"));
        parent::__construct($command[0], MainDuel::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(MainDuel::$config->get("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) {
            $player->sendMessage(MainDuel::$config->get("noplayer"));
            return;
        }

        if (count(DuelAPI::$players) === 2) {
            $player->sendMessage(MainDuel::$config->get("already"));
            return;
        }

        if (isset($args[0])) {
            if ($args[0] === "accept") {
                if (!empty(DuelAPI::$invitation[$player->getName()])) {
                    if (DuelAPI::$invitation[$player->getName()]["time"] > time()) {
                        if (count(DuelAPI::$players) !== 2) {
                            if (Server::getInstance()->getPlayer(DuelAPI::$invitation[$player->getName()]["player"]) !== null) {
                                DuelAPI::startGame($player, Server::getInstance()->getPlayer(DuelAPI::$invitation[$player->getName()]["player"]));
                                unset(DuelAPI::$invitation[$player->getName()]);
                            } else $player->sendMessage(MainDuel::$config->get("offline"));
                        } else $player->sendMessage(MainDuel::$config->get("already"));
                    } else $player->sendMessage(MainDuel::$config->get("expiration"));
                } else $player->sendMessage(MainDuel::$config->get("noinvit"));
            } else DuelForms::mainForm($player);
        } else DuelForms::mainForm($player);
    }
}