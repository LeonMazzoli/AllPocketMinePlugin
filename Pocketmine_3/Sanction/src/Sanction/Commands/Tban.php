<?php

namespace Sanction\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\API\DiscordAPI;
use Sanction\SanctionMain;

class Tban extends PluginCommand
{
    public $sender = [];

    public function __construct(SanctionMain $main)
    {
        parent::__construct("tban", $main);
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $this->setDescription($config->getNested("tban.desc"));
        $this->setPermission($config->getNested("tban.perm"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $data = new Config(SanctionMain::getInstance()->getDataFolder() . "data.json", Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $bandata = new Config(SanctionMain::getInstance()->getDataFolder() . "ban_data.json", Config::JSON);
        if (!$player->hasPermission($config->getNested("tban.perm"))) return $player->sendMessage($config->get("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("tban.nopla"));
        if (!isset($args[1])) return $player->sendMessage($config->getNested("tban.time"));

        $reason = implode(" ", array_splice($args, 2, 99999));
        if (Server::getInstance()->getPlayer($args[0])) {
            $sender = Server::getInstance()->getPlayer($args[0]);
            $name = strtolower($sender->getName());
            $temp = substr("$args[1]", -1);
            $temps = substr("$args[1]", 0, -1);
            if ($temp === "m" or $temp === "h" or $temp === "d" and is_numeric($temps)) {
                switch ($temp) {
                    case "d":
                        $t = $temps * 60 * 60 * 24;
                        break;
                    case "h":
                        $t = $temps * 60 * 60;
                        break;
                    case "m":
                        $t = $temps * 60;
                        break;
                }
                if (isset($reason)) {
                    $info = ["ban" => "temp", "joueur" => strtolower($sender->getName()), "temps" => time() + $t, "raison" => "$reason"];
                    $bandata->setNested(strtolower($sender->getName()), $info);
                    $bandata->save();
                    $data->setNested(strtolower($sender->getName() . ".ban"), $data->getNested(strtolower($sender->getName() . ".ban")) + 1);
                    $data->save();
                    $sender->close(
                        str_replace([strtolower('{player}'), strtolower('{time}'), strtolower('{format}'), strtolower('{reason}')], [$player->getName(), $temps, $temp, $reason], $config->getNested('tban.close')),
                        str_replace([strtolower('{player}'), strtolower('{time}'), strtolower('{format}'), strtolower('{reason}')], [$player->getName(), $temps, $temp, $reason], $config->getNested('tban.close')));
                    $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}'), strtolower('{time}'), strtolower('{format}')], [$sender->getName(), $reason, $temps, $temp], $config->getNested("tban.good")));
                } else return $player->sendMessage($config->getNested("tban.reason"));
            } else return $player->sendMessage($config->getNested("tban.time"));
        } else {
            $sender = strtolower($args[0]);
            $name = $args[0];
            $temp = substr("$args[1]", -1);
            $temps = substr("$args[1]", 0, -1);
            if ($temp === "m" or $temp === "h" or $temp === "d" and is_numeric($temps)) {
                switch ($temp) {
                    case "d":
                        $t = $temps * 60 * 60 * 24;
                        break;
                    case "h":
                        $t = $temps * 60 * 60;
                        break;
                    case "m":
                        $t = $temps * 60;
                        break;
                }
                if (isset($reason)) {
                    $info = ["ban" => "temp", "joueur" => $sender, "temps" => time() + $t, "raison" => "$reason"];
                    $bandata->setNested(strtolower($args[0]), $info);
                    $bandata->save();
                    $player->sendMessage(str_replace([strtolower('{player}'), strtolower('{reason}'), strtolower('{time}'), strtolower('{format}')], [$sender, $reason, $temps, $temp], $config->getNested("tban.good")));
                } else return $player->sendMessage($config->getNested("tban.reason"));
            } else return $player->sendMessage($config->getNested("tban.time"));
        }

        // Embed
        DiscordAPI::discord(str_replace([strtolower('{player}'), strtolower('{sender}'), strtolower('{time}'), strtolower('{reason}'), strtolower('{format}')], [$player->getName(), $name, $temps, $reason, $temp], $config->getNested("tban.embed.desc")), $config->getNested("tban.embed.title"));
        return true;
    }
}