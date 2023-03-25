<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Unmute extends PluginCommand{
    public function __construct(SanctionMain $main)
    {
        parent::__construct("unmute", $main);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $this->setDescription($config->getNested("unmute.desc"));
        $this->setPermission($config->getNested("unmute.perm"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if (!($player->hasPermission($config->getNested("unmute.perm")))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("unmute.nopl"));
        if (Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage($config->getNested("unmute.noplayer"));


        $sender = Server::getInstance()->getPlayer($args[0]);
        if (isset(Mute::$mute[$sender->getName()])){
            unset(Mute::$mute[$sender->getName()]);
            $player->sendMessage(str_replace(strtolower('{player}'), $sender->getName(), $config->getNested("unmute.mess")));
            $sender->sendMessage(str_replace(strtolower('{player}'), $player->getName(), $config->getNested("unmute.messa")));
        }else return $player->sendMessage($config->getNested("unmute.nom"));

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{sender}')], [$player->getName(), $sender->getName()], $config->getNested("unmute.embed.desc")), $config->getNested("unmute.embed.title"));
        return true;
    }
}