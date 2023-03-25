<?php

namespace Digueloulou12\Command;

use pocketmine\Player;
use pocketmine\Server;
use Digueloulou12\Main;
use Digueloulou12\API\ConfigAPI;
use Digueloulou12\API\FactionAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class FactionCommand extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", ConfigAPI::getConfigValue("command"));
        parent::__construct($command[0], Main::getInstance());
        if ((isset($command[1])) and ($command !== " ")) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(ConfigAPI::getConfigValue("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) {
            $player->sendMessage(ConfigAPI::getConfigReplace("console"));
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(ConfigAPI::getConfigReplace("help"));
            return;
        }

        switch ($args[0]) {
            case "make":
            case "create":
                if (!FactionAPI::isInFaction($player)) {
                    if (isset($args[1])) {
                        if (!FactionAPI::existFaction($args[1])) {
                            FactionAPI::createFaction($player, $args[1]);
                            $player->sendMessage(ConfigAPI::getConfigReplace("create_faction"));
                            Server::getInstance()->broadcastMessage(ConfigAPI::getConfigReplace("server_create", ["{faction}"], [$args[1]]));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("exist_faction"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("already_in_faction"));
                break;
            case "del":
            case "delete":
            case "disband":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                        if (!empty(FactionAPI::$chat[$player->getName()])) unset(FactionAPI::$chat[$player->getName()]);
                        Server::getInstance()->broadcastMessage(ConfigAPI::getConfigReplace("server_delete", ["{faction}"], [FactionAPI::getFactionPlayer($player)]));
                        $player->sendMessage(ConfigAPI::getConfigReplace("del_faction"));
                        FactionAPI::disbandFaction(FactionAPI::getFactionPlayer($player));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "invite":
                if (FactionAPI::isInFaction($player)) {
                    if (isset($args[1])) {
                        $sender = Server::getInstance()->getPlayer($args[1]);
                        if ($sender instanceof Player) {
                            if (!FactionAPI::isInFaction($sender)) {
                                if (FactionAPI::countPlayerInFaction(FactionAPI::getFactionPlayer($player)) < ConfigAPI::getConfigValue("max_player")) {
                                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                                        FactionAPI::$invitation[$sender->getName()] = ["fac" => FactionAPI::getFactionPlayer($player), "time" => time() + ConfigAPI::getConfigValue("time_invite")];
                                        $player->sendMessage(ConfigAPI::getConfigReplace("invite_player", ["{player}"], [$sender->getName()]));
                                        $sender->sendMessage(ConfigAPI::getConfigReplace("invite_sender", ["{faction}"], [FactionAPI::getFactionPlayer($player)]));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("stop_player"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("has_faction"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_connected_player"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_player"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "join":
            case "accept":
                if (!FactionAPI::isInFaction($player)) {
                    if (!empty(FactionAPI::$invitation[$player->getName()])) {
                        if (FactionAPI::$invitation[$player->getName()]["time"] > time()) {
                            FactionAPI::addMemberInFaction($player, FactionAPI::$invitation[$player->getName()]["fac"]);
                            unset(FactionAPI::$invitation[$player->getName()]);
                            $player->sendMessage(ConfigAPI::getConfigReplace("join_good"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_valid"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_invite"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("already_in_faction"));
                break;
            case "leave":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) !== $player->getName()) {
                        if (!empty(FactionAPI::$chat[$player->getName()])) unset(FactionAPI::$chat[$player->getName()]);
                        FactionAPI::removePlayerInFaction($player, FactionAPI::getFactionPlayer($player));
                        $player->sendMessage(ConfigAPI::getConfigReplace("leave_good"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_leave"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "desc":
            case "description":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        $desc = implode(" ", array_splice($args, 1, 99999));
                        FactionAPI::setDescriptionFaction($desc, FactionAPI::getFactionPlayer($player));
                        $player->sendMessage(ConfigAPI::getConfigReplace("description"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "c":
            case "chat":
                if (FactionAPI::isInFaction($player)) {
                    if (!empty(FactionAPI::$chat[$player->getName()])) {
                        unset(FactionAPI::$chat[$player->getName()]);
                        $player->sendMessage(ConfigAPI::getConfigReplace("chat_global"));
                    } else {
                        FactionAPI::$chat[$player->getName()] = $player;
                        $player->sendMessage(ConfigAPI::getConfigReplace("chat_faction"));
                    }
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "f":
            case "info":
                if (isset($args[1])) {
                    if (FactionAPI::existFaction($args[1])) {
                        $player->sendMessage(FactionAPI::getMessageInfo($args[1]));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_exist_faction"));
                } else {
                    if (FactionAPI::isInFaction($player)) {
                        $player->sendMessage(FactionAPI::getMessageInfo(FactionAPI::getFactionPlayer($player)));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_faction"));
                }
                break;
            case "givelead":
            case "leader":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                        if (isset($args[1])) {
                            $sender = Server::getInstance()->getPlayer($args[1]);
                            if ($sender instanceof Player) {
                                if (FactionAPI::getFactionPlayer($sender) === FactionAPI::getFactionPlayer($player)) {
                                    FactionAPI::setOwnerFaction($sender, FactionAPI::getFactionPlayer($player));
                                    $player->sendMessage(ConfigAPI::getConfigReplace("msg_player"));
                                    $sender->sendMessage(ConfigAPI::getConfigReplace("msg_sender"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_in_faction"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_connected_player"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_player"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "kick":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        if (isset($args[1])) {
                            $sender = Server::getInstance()->getPlayer($args[1]);
                            if ($sender instanceof Player) {
                                if (FactionAPI::getFactionPlayer($sender) === FactionAPI::getFactionPlayer($player)) {
                                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) !== $sender->getName()) {
                                        if (!empty(FactionAPI::$chat[$sender->getName()])) unset(FactionAPI::$chat[$sender->getName()]);
                                        FactionAPI::removePlayerInFaction($sender, FactionAPI::getFactionPlayer($player));
                                        FactionAPI::removePowerFaction(FactionAPI::getFactionPlayer($player), FactionAPI::$power->get($sender->getName()));
                                        $player->sendMessage(ConfigAPI::getConfigReplace("kick"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_kick"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_in_faction"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_connected_player"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_player"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "name":
            case "rename":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                        if (isset($args[1])) {
                            if (!FactionAPI::existFaction($args[1])) {
                                FactionAPI::renameFaction(FactionAPI::getFactionPlayer($player), $args[1]);
                                $player->sendMessage(ConfigAPI::getConfigReplace("rename_good"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("exist_faction"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("rename_no"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "member":
            case "members":
                if (FactionAPI::isInFaction($player)) {
                    $members = implode(", ", FactionAPI::getMembersFaction(FactionAPI::getFactionPlayer($player)));
                    $player->sendMessage(ConfigAPI::getConfigReplace("member", ["{members}"], [$members]));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "sethome":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        FactionAPI::setHome($player, FactionAPI::getFactionPlayer($player));
                        $player->sendMessage(ConfigAPI::getConfigReplace("sethome_good"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "h":
            case "home":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::hasHome(FactionAPI::getFactionPlayer($player))) {
                        $player->teleport(FactionAPI::getPostitionHome(FactionAPI::getFactionPlayer($player)));
                        $player->sendMessage(ConfigAPI::getConfigReplace("home_good"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_home"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
            case "promote":
                if (FactionAPI::isInFaction($player)) {
                    if (isset($args[1])) {
                        $sender = Server::getInstance()->getPlayer($args[1]);
                        if ($sender instanceof Player) {
                            if (FactionAPI::getFactionPlayer($sender) === FactionAPI::getFactionPlayer($player)) {
                                if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                                    if (FactionAPI::getRankPlayerChatInFaction($sender, FactionAPI::getFactionPlayer($player)) !== "*") {
                                        if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $sender->getName()) {
                                            FactionAPI::promotePlayerInFaction($sender, FactionAPI::getFactionPlayer($player));
                                            $player->sendMessage(ConfigAPI::getConfigReplace("promote_good"));
                                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_promote"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_promote"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_in_faction"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_connected_player"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_player"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "demote":
                if (FactionAPI::isInFaction($player)) {
                    if (isset($args[1])) {
                        $sender = Server::getInstance()->getPlayer($args[1]);
                        if ($sender instanceof Player) {
                            if (FactionAPI::getFactionPlayer($sender) === FactionAPI::getFactionPlayer($player)) {
                                if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                                    if (FactionAPI::getRankPlayerChatInFaction($sender, FactionAPI::getFactionPlayer($player)) === "*") {
                                        FactionAPI::demotePlayerInFaction($sender, FactionAPI::getFactionPlayer($player));
                                        $player->sendMessage(ConfigAPI::getConfigReplace("demote_good"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_demote"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_in_faction"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_connected_player"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_player"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "chunk":
                if (!empty(FactionAPI::$chunk[$player->getName()])) {
                    unset(FactionAPI::$chunk[$player->getName()]);
                    $player->sendMessage(ConfigAPI::getConfigReplace("chunk_unvisible"));
                } else {
                    FactionAPI::$chunk[$player->getName()] = $player->getName();
                    $player->sendMessage(ConfigAPI::getConfigReplace("chunk_visible"));
                }
                break;
            case "top":
            case "topfaction":
                FactionAPI::sendTopFaction($player);
                break;
            case "claim":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        if (ConfigAPI::getConfig()->get("level_claim") === $player->getLevel()->getName()) {
                            if (!FactionAPI::isChunkClaim($player->getLevel()->getChunkAtPosition($player))) {
                                if (FactionAPI::getPowerFaction(FactionAPI::getFactionPlayer($player)) >= ConfigAPI::getConfigValue("min_power_for_claim")) {
                                    FactionAPI::claimChunk($player->getLevel()->getChunkAtPosition($player), FactionAPI::getFactionPlayer($player));
                                    $player->sendMessage(ConfigAPI::getConfigReplace("claim_good"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_power"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("already_claim", ["{faction}"], [FactionAPI::getFactionClaim($player->getLevel()->getChunkAtPosition($player))]));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_world_claim"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "unclaim":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        if (ConfigAPI::getConfig()->get("level_claim") === $player->getLevel()->getName()) {
                            if (FactionAPI::isChunkClaim($player->getLevel()->getChunkAtPosition($player))) {
                                if (FactionAPI::getFactionClaim($player->getLevel()->getChunkAtPosition($player)) === FactionAPI::getFactionPlayer($player)) {
                                    FactionAPI::unclaimChunk($player->getLevel()->getChunkAtPosition($player));
                                    $player->sendMessage(ConfigAPI::getConfigReplace("unclaim_good"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_claim_you"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_claim"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_world_claim"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "fly":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::isChunkClaim($player->getLevel()->getChunkAtPosition($player))) {
                        if (FactionAPI::getFactionClaim($player->getLevel()->getChunkAtPosition($player)) === FactionAPI::getFactionPlayer($player)) {
                            if (!empty(FactionAPI::$fly[$player->getName()])) {
                                unset(FactionAPI::$fly[$player->getName()]);
                                $player->setFlying(false);
                                $player->setAllowFlight(false);
                                $player->sendMessage(ConfigAPI::getConfigReplace("fly_off"));
                            } else {
                                $player->setAllowFlight(true);
                                $player->setFlying(true);
                                FactionAPI::$fly[$player->getName()] = $player->getName();
                                $player->sendMessage(ConfigAPI::getConfigReplace("fly_on"));
                            }
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_claim_you"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_claim"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "overclaim":
                if (FactionAPI::isInFaction($player)) {
                    if ((FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "*") or
                        (FactionAPI::getRankPlayerChatInFaction($player, FactionAPI::getFactionPlayer($player)) === "**")) {
                        if (ConfigAPI::getConfig()->get("level_claim") === $player->getLevel()->getName()) {
                            if (FactionAPI::isChunkClaim($player->getLevel()->getChunkAtPosition($player))) {
                                $power = FactionAPI::getPowerFaction(FactionAPI::getFactionClaim($player->getLevel()->getChunkAtPosition($player))) + ConfigAPI::getConfigValue("owerclaim_min");
                                if (FactionAPI::getPowerFaction(FactionAPI::getFactionPlayer($player)) >= $power) {
                                    $sender = FactionAPI::getOwnerPlayer(FactionAPI::getFactionClaim($player->getLevel()->getChunkAtPosition($player)));
                                    if ($sender instanceof Player) $sender->sendMessage(ConfigAPI::getConfigReplace("overclaim_owner"));
                                    FactionAPI::unclaimChunk($player->getLevel()->getChunkAtPosition($player), true);
                                    FactionAPI::claimChunk($player->getLevel()->getChunkAtPosition($player), FactionAPI::getFactionPlayer($player), true);
                                    $player->sendMessage(ConfigAPI::getConfigReplace("overclaim"));
                                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_overclaim"));
                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_claim"));
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_world_claim"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_perm_in_faction"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            case "ally":
            case "allie":
            case "allies":
                if (FactionAPI::isInFaction($player)) {
                    if (FactionAPI::getOwnerPlayer(FactionAPI::getFactionPlayer($player)) === $player->getName()) {
                        if (isset($args[1])) {
                            switch ($args[1]) {
                                case "accept":
                                    if (!empty(FactionAPI::$invitation_ally[FactionAPI::getFactionPlayer($player)])) {
                                        if (FactionAPI::$invitation_ally[FactionAPI::getFactionPlayer($player)]["time"] > time()) {
                                            if (!in_array($args[1], FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($player)))) {
                                                FactionAPI::addAlly(FactionAPI::getFactionPlayer($player), FactionAPI::$invitation_ally[FactionAPI::getFactionPlayer($player)]["faction"]);
                                                $player->sendMessage(ConfigAPI::getConfigReplace("ally_good"));
                                            } else $player->sendMessage(ConfigAPI::getConfigReplace("already_ally"));
                                        } else $player->sendMessage(ConfigAPI::getConfigReplace("expired_ally"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_ally"));
                                    break;
                                case "del":
                                    if (isset($args[2])) {
                                        if (FactionAPI::existFaction($args[2])) {
                                            if (in_array($args[2], FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($player)))) {
                                                FactionAPI::removeAlly(FactionAPI::getFactionPlayer($player), $args[2]);
                                                $player->sendMessage(ConfigAPI::getConfigReplace("unally_good"));
                                            } else $player->sendMessage(ConfigAPI::getConfigReplace("no_ally_faction"));
                                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_exist_faction"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_args_faction"));
                                    break;
                                case "list":
                                    if (isset($args[2])) {
                                        if (FactionAPI::existFaction($args[2])) {
                                            $allies = implode(", ", FactionAPI::getAllyFaction($args[2]));
                                            $player->sendMessage(ConfigAPI::getConfigReplace("list_ally", ["{faction}", "{ally}"], [$args[2], $allies]));
                                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_exist_faction"));
                                    } else {
                                        $allies = implode(", ", FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($player)));
                                        $player->sendMessage(ConfigAPI::getConfigReplace("list_ally", ["{faction}", "{ally}"], [FactionAPI::getFactionPlayer($player), $allies]));
                                    }
                                    break;
                                default:
                                    if (FactionAPI::existFaction($args[1])) {
                                        $sender = Server::getInstance()->getPlayer(FactionAPI::getOwnerPlayer($args[1]));
                                        if ($sender instanceof Player) {
                                            if (!in_array($args[1], FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($player)))) {
                                                FactionAPI::$invitation_ally[$args[1]] = ["faction" => FactionAPI::getFactionPlayer($player), "time" => time() + ConfigAPI::getConfigValue("time_invite_ally")];
                                                $sender->sendMessage(ConfigAPI::getConfigReplace("ally_sender", ["{faction}"], [FactionAPI::getFactionPlayer($player)]));
                                                $player->sendMessage(ConfigAPI::getConfigReplace("ally_player", ["{faction}"], [$args[1]]));
                                            } else $player->sendMessage(ConfigAPI::getConfigReplace("already_ally"));
                                        } else $player->sendMessage(ConfigAPI::getConfigReplace("no_owner_online"));
                                    } else $player->sendMessage(ConfigAPI::getConfigReplace("no_exist_faction"));
                                    break;
                            }
                        } else $player->sendMessage(ConfigAPI::getConfigReplace("ally_help"));
                    } else $player->sendMessage(ConfigAPI::getConfigReplace("require_owner"));
                } else $player->sendMessage(ConfigAPI::getConfigReplace("no_faction"));
                break;
            default:
                $player->sendMessage(ConfigAPI::getConfigReplace("help"));
                break;
        }
    }
}