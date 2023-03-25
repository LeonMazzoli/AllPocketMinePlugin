<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Mute extends PluginCommand{
    public static $mute = [];
    private static $reason = [];
    public function __construct(SanctionMain $main)
    {
        parent::__construct("tmute", $main);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $this->setDescription($config->getNested("mute.desc"));
        $this->setPermission($config->getNested("mute.perm"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $data = new Config(SanctionMain::getInstance()->getDataFolder()."data.json",Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $reason = implode(" ", array_splice($args, 2, 99999));
        if (!$player->hasPermission($config->getNested("mute.perm"))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("mute.player"));
        if (!isset($args[1])) return $player->sendMessage($config->getNested("mute.notime"));
        if (!isset($reason)) return $player->sendMessage($config->getNested("mute.noreason"));
        if (!is_numeric($args[1])) return $player->sendMessage($config->getNested("mute.numeric"));
        if (Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage($config->getNested("mute.noplayer"));


        $time = $args[1] * 60;
        $sender = Server::getInstance()->getPlayer($args[0]);
        $name = strtolower($sender->getName());
        if (empty(self::$mute[$sender->getName()]) or self::$mute[$sender->getName()] <= time()){
            self::$mute[$sender->getName()] = time() + $time;
            self::$reason[$sender->getName()] = $reason;
            $player->sendMessage(str_replace([strtolower('{time}'), strtolower('{player}'), strtolower('{reason}')], [$args[1], $sender->getName(), $reason], $config->getNested("mute.plm")));
            $sender->sendMessage(str_replace([strtolower('{time}'), strtolower('{player}'), strtolower('{reason}')], [$args[1], $player->getName(), $reason], $config->getNested("mute.sem")));
            $data->setNested("$name.mute", $data->getNested("$name.mute") + 1);
            $data->save();
        }else return $player->sendMessage($config->getNested("mute.al"));

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{sender}'), strtolower('{time}'), strtolower('{reason}')], [$player->getName(), $sender->getName(), $args[1], self::$reason[$sender->getName()]], $config->getNested("mute.embed.desc")), $config->getNested("mute.embed.title"));
        return true;
    }
}