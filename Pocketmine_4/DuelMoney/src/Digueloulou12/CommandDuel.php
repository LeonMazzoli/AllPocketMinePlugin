<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class CommandDuel extends Command
{
    public function __construct()
    {
        $command = explode(":", MainDuel::$config->get("command"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
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

        if ((isset($args[0])) and ($args[0] === "accept")) {
            if (!empty(DuelAPI::$invitation[$player->getName()])) {
                if (DuelAPI::$invitation[$player->getName()]["time"] > time()) {
                    if (count(DuelAPI::$players) !== 2) {
                        if (Server::getInstance()->getPlayerByPrefix(DuelAPI::$invitation[$player->getName()]["player"]) !== null) {
                            if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player) >= DuelAPI::$invitation[$player->getName()]["money"]) {
                                if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney(DuelAPI::$invitation[$player->getName()]["player"]) >= DuelAPI::$invitation[$player->getName()]["money"]) {
                                    DuelAPI::startGame($player, Server::getInstance()->getPlayerByPrefix(DuelAPI::$invitation[$player->getName()]["player"]), DuelAPI::$invitation[$player->getName()]["money"]);
                                    unset(DuelAPI::$invitation[$player->getName()]);
                                } else $player->sendMessage(MainDuel::$config->get("no_money_"));
                            } else $player->sendMessage(MainDuel::$config->get("no_money"));
                        } else $player->sendMessage(MainDuel::$config->get("offline"));
                    } else $player->sendMessage(MainDuel::$config->get("already"));
                } else $player->sendMessage(MainDuel::$config->get("expiration"));
            } else $player->sendMessage(MainDuel::$config->get("noinvit"));
        } else {
            if (isset($args[0])) {
                if (is_numeric($args[0])) {
                    if ($args[0] >= 100) {
                        if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player) >= $args[0]) {
                            DuelForms::mainForm($player, $args[0]);
                        } else $player->sendMessage(MainDuel::$config->get("no_money"));
                    } else $player->sendMessage(MainDuel::$config->get("100"));
                } else $player->sendMessage(MainDuel::$config->get("is_numeric"));
            } else $player->sendMessage(MainDuel::$config->get("no_args_money"));
        }
    }
}