<?php

namespace Report\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Report\Forms\ReportForm;
use Report\ReportMain;

class Report extends PluginCommand{
    public function __construct(ReportMain $main)
    {
        $info = explode(":", $main->getConfigValue("report"));
        parent::__construct($info[0], $main);
        if (isset($info[1])) $this->setDescription($info[1]);
        if (isset($info[2])) $this->setPermission($info[2]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $info = explode(":", ReportMain::getInstance()->getConfigValue("report"));
        if ($player instanceof Player){
            if (isset($info[2])){
                if ($player->hasPermission($info[2])){
                    ReportForm::report($player);
                }else $player->sendMessage(ReportMain::getInstance()->getConfigValue("noperm"));
            }else ReportForm::report($player);
        }else $player->sendMessage(ReportMain::getInstance()->getConfigValue("noplayer"));
    }
}