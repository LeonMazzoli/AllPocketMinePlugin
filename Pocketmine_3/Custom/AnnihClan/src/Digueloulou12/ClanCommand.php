<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class ClanCommand extends PluginCommand
{
    public function __construct(MainTeam $main)
    {
        $command = explode(":", $main->config->get("command"));
        parent::__construct($command[0], $main);
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        if ($main->config->get("command_aliases") !== null) $this->setAliases($main->config->get("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) {
            $player->sendMessage(MainTeam::getInstance()->config->get("no_player"));
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(MainTeam::getInstance()->config->get("noargs"));
            return;
        }

        $config = MainTeam::getInstance()->config;
        $data = new Config(MainTeam::getInstance()->getDataFolder() . "data.json", Config::JSON);
        switch ($args[0]) {
            case "create":
                if ($player->isOp()) {
                    if (isset($args[1])) {
                        if (ClanAPI::existClan($args[1]) === false) {
                            $info = ["Name" => $args[1], "Owner" => $player->getName(), "Members" => [$player->getName()]];
                            $data->setNested("clan.$args[1]", $info);
                            $data->save();
                            $player->sendMessage(str_replace(strtolower("{clan}"), $args[1], $config->get("creategood")));
                        } else $player->sendMessage($config->get("al"));
                    } else $player->sendMessage($config->get("noinfo"));
                } else $player->sendMessage($config->get("noperm"));
                break;
            case "delete":
            case "del":
                if ($player->isOp()) {
                    if (isset($args[1])) {
                        if (ClanAPI::existClan($args[1]) === true) {
                            $data->removeNested("clan.$args[1]");
                            $data->save();
                            $player->sendMessage(str_replace(strtolower("{clan}"), $args[1], $config->getNested("gooddel")));
                        } else $player->sendMessage($config->get("noex"));
                    } else $player->sendMessage($config->get("noinfo"));
                } else $player->sendMessage($config->get("noperm"));
                break;
            case "join":
                if (isset($args[1])) {
                    if ($data->getNested("clan.$args[1]") !== null) {
                        if (!ClanAPI::playerExistInClan($player)) {
                            $clan = $data->getNested("clan.$args[1].Members");
                            array_push($clan, $player->getName());
                            $data->setNested("clan." . $args[1] . ".Members", $clan);
                            $data->save();
                            $player->sendMessage(str_replace(strtolower('{clan}'), $args[1], $config->get("join")));
                        } else $player->sendMessage($config->get("alclan"));
                    } else $player->sendMessage($config->get("noex"));
                } else $player->sendMessage($config->get("noinfo"));
                break;
            default:
                $player->sendMessage($config->get("nocommand"));
                break;
        }
    }
}