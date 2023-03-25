<?php

namespace Digueloulou12\Commands;

use Digueloulou12\API\MoneyAPI;
use Digueloulou12\API\SkyblockAPI;
use Digueloulou12\Forms\SkyblockForms;
use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Is extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("is"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("is_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("is"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!isset($args[0])) {
            SkyblockForms::mainForm($player);
            // $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noargs_msg"));
            return;
        }

        switch ($args[0]) {
            case "create":
                if (!SkyblockAPI::isInIsland($player)) {
                    if (isset($args[1])) {
                        if (Server::getInstance()->getLevelByName($args[1]) === null) {
                            SkyblockAPI::createIsland($player, $args[1]);
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_create_msg"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_already_exist"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_title"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_already_in_is"));
                break;
            case "del":
            case "delete":
            case "disband":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getOwnerPlayer(SkyblockAPI::getIslandPlayer($player)) === $player->getName()) {
                        SkyblockAPI::disbandIsland(SkyblockAPI::getIslandPlayer($player));
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_disband_msg"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noowner_msg"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "tp":
            case "go":
                if (SkyblockAPI::isInIsland($player)) {
                    if (!Server::getInstance()->isLevelLoaded(SkyblockAPI::getIslandPlayer($player))) Server::getInstance()->loadLevel(SkyblockAPI::getIslandPlayer($player));
                    $player->teleport(SkyblockAPI::getSpawnIsland(SkyblockAPI::getIslandPlayer($player)));
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_tpgood"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "bank":
                if (Main::$config->get("money") === true) {
                    if (SkyblockAPI::isInIsland($player)) {
                        if (isset($args[1])) {
                            switch ($args[1]) {
                                case "deposit":
                                    if (isset($args[2])) {
                                        if (is_numeric($args[2])) {
                                            if ($args[2] > 0) {
                                                if (MoneyAPI::getMoney($player) >= $args[2]) {
                                                    MoneyAPI::removeMoney($player, $args[2]);
                                                    SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player), $args[2], "add");
                                                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_deposit", [strtolower("{money}")], [$args[2]]));
                                                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("pay_nomoney_msg"));
                                            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_negative_value"));
                                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
                                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
                                    break;
                                case "withdraw":
                                    if (isset($args[2])) {
                                        if (is_numeric($args[2])) {
                                            if ($args[2] > 0) {
                                                if (SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player)) >= $args[2]) {
                                                    if (SkyblockAPI::getRankPlayer($player) !== "Membre") {
                                                        MoneyAPI::addMoney($player, $args[2]);
                                                        SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player), $args[2], "remove");
                                                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_withdraw", [strtolower("{money}")], [$args[2]]));
                                                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_officier_requis"));
                                                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_nomoney"));
                                            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_negative_value"));
                                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
                                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
                                    break;
                                default:
                                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_noargs"));
                                    break;
                            }
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_status", [strtolower("{money}")], [SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player))]));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_nobank"));
                break;
            case "setspawn":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getRankPlayer($player) !== "Membre") {
                        if ($player->getLevel()->getName() === SkyblockAPI::getIslandPlayer($player)) {
                            SkyblockAPI::setSpawnIsland($player, SkyblockAPI::getIslandPlayer($player));
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_setspawn"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_world"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_officier_requis"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "lock":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getRankPlayer($player) !== "Membre") {
                        if (SkyblockAPI::$config->get(SkyblockAPI::getIslandPlayer($player))["Lock"] === true) {
                            SkyblockAPI::$config->setNested(SkyblockAPI::getIslandPlayer($player) . ".Lock", false);
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_unlock"));
                        } else {
                            SkyblockAPI::$config->setNested(SkyblockAPI::getIslandPlayer($player) . ".Lock", true);
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_lock"));
                        }
                        SkyblockAPI::$config->save();
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_officier_requis"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "members":
                if (SkyblockAPI::isInIsland($player)) {
                    $members = implode(", ", SkyblockAPI::getAllMembers(SkyblockAPI::getIslandPlayer($player)));
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_members", [strtolower("{members}")], [$members]));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "leave":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getRankPlayer($player) !== "Owner") {
                        SkyblockAPI::removePlayerIsland($player, SkyblockAPI::getIslandPlayer($player));
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_leave"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_owner_no"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "transfer":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getOwnerPlayer(SkyblockAPI::getIslandPlayer($player)) === $player->getName()) {
                        if (isset($args[1])) {
                            if (in_array($args[1], SkyblockAPI::getAllMembers(SkyblockAPI::getIslandPlayer($player)))) {
                                SkyblockAPI::setOwnerIsland($args[1], SkyblockAPI::getIslandPlayer($player));
                                $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_transfer", [strtolower("{player}")], [$args[1]]));
                            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_nomember"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_args_player"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noowner_msg"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "invite":
                if (SkyblockAPI::isInIsland($player)) {
                    if (count(SkyblockAPI::getAllMembers(SkyblockAPI::getIslandPlayer($player))) < Main::$config->get("is_member_limit")) {
                        if (isset($args[1])) {
                            $sender = Server::getInstance()->getPlayer($args[1]);
                            if ($sender instanceof Player) {
                                if (!SkyblockAPI::isInIsland($sender)) {
                                    SkyblockAPI::$invitation[$sender->getName()] = ["time" => time() + Main::$config->get("is_invitation"), "island" => SkyblockAPI::getIslandPlayer($player)];
                                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_invite", [strtolower("{player}")], [$sender->getName()]));
                                    $sender->sendMessage(Main::getConfigAPI()->getConfigValue("is_invite2", [strtolower("{player}")], [$player->getName()]));
                                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_invite_al"));
                            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noplayer_invite"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_args_player"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_member_max"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            case "accept":
                if (!SkyblockAPI::isInIsland($player)) {
                    if (!empty(SkyblockAPI::$invitation[$player->getName()])) {
                        if (SkyblockAPI::$invitation[$player->getName()]["time"] > time()) {
                            SkyblockAPI::addMemberIsland($player, SkyblockAPI::$invitation[$player->getName()]["island"]);
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_join", [strtolower("{island}")], [SkyblockAPI::$invitation[$player->getName()]["island"]]));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_invite_expired"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_not_invite"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_already_in_is"));
                break;
            case "kick":
                if (SkyblockAPI::isInIsland($player)) {
                    if (SkyblockAPI::getRankPlayer($player) !== "Membre") {
                        if (isset($args[1])) {
                            if (in_array($args[1], SkyblockAPI::getAllMembers(SkyblockAPI::getIslandPlayer($player)))) {
                                if (SkyblockAPI::getOwnerPlayer(SkyblockAPI::getIslandPlayer($player)) !== $args[1]) {
                                    SkyblockAPI::removePlayerIsland($args[1], SkyblockAPI::getIslandPlayer($player));
                                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_kick", [strtolower("{player}")], [$args[1]]));
                                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_kick_owner"));
                            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_nomember"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_args_player"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_officier_requis"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                break;
            default:
                SkyblockForms::mainForm($player);
                // $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noargs_msg"));
                break;
        }
    }
}