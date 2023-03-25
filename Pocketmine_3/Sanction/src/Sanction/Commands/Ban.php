<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Ban extends PluginCommand{
    public function __construct(SanctionMain $main)
    {
        parent::__construct("ban", $main);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $this->setDescription($config->getNested("ban.desc"));
        $this->setPermission($config->getNested("ban.perm"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $bandata = new Config(SanctionMain::getInstance()->getDataFolder()."ban_data.json",Config::JSON);
        if (!$player->hasPermission($config->getNested("ban.perm"))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("ban.nopl"));
        if (!isset($args[1])) return $player->sendMessage($config->getNested("ban.nore"));


        $reason = implode(" ", array_splice($args, 1, 99999));
        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $sender = Server::getInstance()->getPlayer($args[0]);
            $name = strtolower($sender->getName());
            if (!$bandata->exists($name)){
                $bandata->setNested("$name.ban", "perm");
                $bandata->setNested("$name.reason", $reason);
                $bandata->save();
                $sender->close(str_replace([strtolower('{player}'), strtolower('{reason}')], [$player->getName(), $reason], $config->getNested("ban.close")),
                               str_replace([strtolower('{player}'), strtolower('{reason}')], [$player->getName(), $reason], $config->getNested("ban.close")));
                $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}')], [$sender->getName(), $reason], $config->getNested("ban.good")));
            }else return $player->sendMessage($config->getNested("ban.al"));
        }else{
            $name = strtolower($args[0]);
            if (!$bandata->exists($name)){
                $bandata->setNested("$name.ban", "perm");
                $bandata->setNested("$name.reason", $reason);
                $bandata->save();
                $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}')], [$name, $reason], $config->getNested("ban.good")));
            }else return $player->sendMessage($config->getNested("ban.al"));
        }

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{sender}'), strtolower('{reason}')], [$player->getName(), $name, $reason], $config->getNested("ban.embed.desc")), $config->getNested("ban.embed.title"));
        return true;
    }
}