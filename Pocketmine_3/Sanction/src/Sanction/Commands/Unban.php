<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Unban extends PluginCommand
{
    public function __construct(SanctionMain $main)
    {
        $config = new Config($main->getDataFolder()."config.yml",Config::YAML);
        parent::__construct("unban", $main);
        $this->setPermission($config->getNested("unban.perm"));
        $this->setDescription($config->getNested("unban.desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $banipdata = new Config(SanctionMain::getInstance()->getDataFolder() . "banip_data.json", Config::JSON);
        $bandata = new Config(SanctionMain::getInstance()->getDataFolder() . "ban_data.json", Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);

        if (!$player->hasPermission($config->getNested("unban.perm"))) {
            $player->sendMessage(SanctionMain::getConfigValue("noperm"));
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage($config->getNested("unban.noargs"));
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage($config->getNested("unban.noargs2"));
            return;
        }

        $name = strtolower($args[1]);

        switch ($args[0]) {
            case "ip":
                if ($banipdata->exists($args[1])) {
                    $banipdata->remove($args[1]);
                    $banipdata->save();

                    $player->sendMessage(str_replace(strtolower("{ip}"), $args[1], $config->getNested("unban.unbanipgood")));
                    DiscordAPI::discord(str_replace([strtolower("{ip}"), strtolower("{player}")], [$args[1], $player->getName()], $config->getNested("unban.ipd")), $config->getNested("unban.title"));
                }else $player->sendMessage($config->getNested("unban.nobanip"));
                break;
            case "perm":
                if ($bandata->exists($name)) {
                    if ($bandata->getNested("$name.ban") === "perm") {
                        $bandata->remove($name);
                        $bandata->save();
                        $player->sendMessage(str_replace(strtolower("{player}"), $name, $config->getNested("unban.good")));
                        DiscordAPI::discord(str_replace([strtolower("{sender}"), strtolower("{player}")], [$name, $player->getName()], $config->getNested("unban.permd")), $config->getNested("unban.title"));
                    }else $player->sendMessage($config->getNested("unban.nobanp"));
                }else $player->sendMessage($config->getNested("unban.noban"));
                break;
            case "temp":
                if ($bandata->exists($name)) {
                    if ($bandata->getNested("$name.ban") === "temp") {
                        $bandata->remove($name);
                        $bandata->save();
                        $player->sendMessage(str_replace(strtolower("{player}"), $name, $config->getNested("unban.good")));
                        DiscordAPI::discord(str_replace([strtolower("{sender}"), strtolower("{player}")], [$name, $player->getName()], $config->getNested("unban.tempd")), $config->getNested("unban.title"));
                    }else $player->sendMessage($config->getNested("unban.nobant"));
                }else $player->sendMessage($config->getNested("unban.noban"));
                break;
            default:
                $player->sendMessage($config->getNested("unban.noargs"));
                break;
        }
    }
}