<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Kick extends PluginCommand{
    public function __construct(SanctionMain $main)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        parent::__construct("kick", $main);
        $this->setPermission($config->getNested("kick.perm"));
        $this->setDescription($config->getNested("kick.desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $kick = new Config(SanctionMain::getInstance()->getDataFolder()."data.json",Config::JSON);
        if (!$player->hasPermission($config->getNested("kick.perm"))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("kick.noargs"));
        if (!isset($args[1])) return $player->sendMessage($config->getNested("kick.noargs"));
        if (Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage($config->getNested("kick.playernull"));


        $sender = Server::getInstance()->getPlayer($args[0]);
        $name = strtolower($sender->getName());
        $kick->setNested("$name.kick", $kick->getNested("$name.kick") + 1);
        $kick->save();
        $reason = implode(" ", array_splice($args, 1, 99999));
        $sender->close(
            str_replace([strtolower('{player}'), strtolower('{reason}')], [$player->getName(), $reason], $config->getNested("kick.kick")),
            str_replace([strtolower('{player}'), strtolower('{reason}')], [$player->getName(), $reason], $config->getNested("kick.kick")));
        $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}')], [$player->getName(), $reason], $config->getNested("kick.good")));

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{sender}'), strtolower('{kick}')], [$player->getName(), $sender->getName(), $reason], $config->getNested("kick.embed.desc")), $config->getNested("kick.embed.title"));
        return true;
    }
}