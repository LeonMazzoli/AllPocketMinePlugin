<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Banip extends PluginCommand
{
    public function __construct(SanctionMain $main)
    {
        parent::__construct("banip", $main);
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $this->setPermission($config->getNested("banip.perm"));
        $this->setDescription($config->getNested("banip.desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $data = new Config(SanctionMain::getInstance()->getDataFolder() . "data.json", Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $banipdata = new Config(SanctionMain::getInstance()->getDataFolder() . "banip_data.json", Config::JSON);
        $reason = implode(" ", array_splice($args, 1, 99999));
        if (!$player->hasPermission($config->getNested("banip.perm"))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("banip.noip"));
        if (!isset($reason)) return $player->sendMessage($config->getNested("banip.noreason"));


        if (Server::getInstance()->getPlayer($args[0]) !== null) {
            $sender = Server::getInstance()->getPlayer($args[0]);
            $ip = $sender->getAddress();
            $banipdata->set($ip, $reason);
            $banipdata->save();
            $data->setNested(strtolower($sender->getName() . ".banip"), $data->getNested(strtolower($sender->getName() . ".banip")) + 1);
            $data->save();
            $sender->close($config->getNested("banip.close"), $config->getNested("banip.close"));
            $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}')], [$sender->getName(), $reason], $config->getNested("banip.goodpl")));
        } else {
            $ip = $args[0];
            if (!$banipdata->exists($args[0])) {
                $banipdata->set($args[0], $reason);
                $banipdata->save();
                $player->sendMessage(str_replace([strtolower('{ip}'), strtolower('{reason}')], [$args[0], $reason], $config->getNested("banip.ipg")));
            } else return $player->sendMessage($config->getNested("banip.ipn"));
        }

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{ip}'), strtolower('{reason}')], [$player->getName(), $ip, $reason], $config->getNested("banip.embed.desc")), $config->getNested("banip.embed.title"));
        return true;
    }
}