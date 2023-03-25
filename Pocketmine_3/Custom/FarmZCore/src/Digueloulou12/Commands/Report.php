<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Forms\ReportForms;
use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Report extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("report"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("report_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $info = explode(":", Main::getConfigAPI()->getConfigValue("report"));
        if ($player instanceof Player) {
            if (isset($info[2])) {
                if ($player->hasPermission($info[2])) {
                    ReportForms::reportForm($player);
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
            } else ReportForms::reportForm($player);
        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
    }
}