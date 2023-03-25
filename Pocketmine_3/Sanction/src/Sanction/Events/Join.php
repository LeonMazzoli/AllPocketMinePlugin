<?php

namespace Sanction\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use Sanction\SanctionMain;

class Join implements Listener
{
    public function onJoin(PlayerPreLoginEvent $event)
    {
        $name = strtolower($event->getPlayer()->getName());
        $player = $event->getPlayer();
        $ip = $player->getAddress();

        $banipdata = new Config(SanctionMain::getInstance()->getDataFolder() . "banip_data.json", Config::JSON);
        $bandata = new Config(SanctionMain::getInstance()->getDataFolder() . "ban_data.json", Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $data = new Config(SanctionMain::getInstance()->getDataFolder() . "data.json", Config::JSON);

        if ($banipdata->exists($ip)) {
            $player->close(str_replace(strtolower('{reason}'), $banipdata->get($ip), $config->getNested("banip.join")),
                str_replace(strtolower('{reason}'), $banipdata->get($ip), $config->getNested("banip.join")));
        }

        if ($bandata->exists($name)) {
            if ($bandata->getNested("$name.ban") === "perm") {
                $player->close(str_replace(strtolower('{reason}'), $bandata->getNested("$name.reason"), $config->getNested("ban.join")),
                    str_replace(strtolower('{reason}'), $bandata->getNested("$name.reason"), $config->getNested("ban.join")));
            } else {
                $c = $bandata->get($name);
                if ($c["temps"] > time()) {
                    $remainingTime = $c["temps"] - time();
                    $day = floor($remainingTime / 86400);
                    $hourSeconds = $remainingTime % 86400;
                    $hour = floor($hourSeconds / 3600);
                    $minuteSec = $hourSeconds % 3600;
                    $minute = floor($minuteSec / 60);
                    $player->close(str_replace([strtolower('{day}'), strtolower('{hour}'), strtolower('{minute}'), strtolower('{reason}')], [$day, $hour, $minute, $bandata->getNested(strtolower($player->getName()) . ".raison")], $config->getNested("tban.join")),
                        str_replace([strtolower('{day}'), strtolower('{hour}'), strtolower('{minute}'), strtolower('{reason}')], [$day, $hour, $minute, $bandata->getNested(strtolower($player->getName()) . ".raison")], $config->getNested("tban.join")));
                }
            }
        }

        if (!$data->exists($name)) {
            $data->setNested("$name.kick", 0);
            $data->setNested("$name.mute", 0);
            $data->setNested("$name.ban", 0);
            $data->setNested("$name.banip", 0);
            $data->save();
        }
    }
}