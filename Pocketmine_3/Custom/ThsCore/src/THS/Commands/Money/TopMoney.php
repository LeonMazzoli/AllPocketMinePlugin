<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\Config;
use THS\API\LanguageAPI;
use THS\Main;

class TopMoney extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("topmoney", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder()."money.json",Config::JSON);
        $player->sendMessage("§7------- §fTop §a10 §fMoney §7-------");
        $configs = $config->getAll();
        arsort($configs);
        $i = 1;
        foreach ($configs as $name => $money) {
            if ($i === 11) {
                break;
            } else {
                if (LanguageAPI::getLanguage($player) === "fr"){
                    $player->sendMessage("§7» §f#$i -> §a$name §favec §a{$money}§f$");
                }else $player->sendMessage("§7» §f#$i -> §a$name §fwith §a{$money}§f$");
                $i++;
            }
        }
    }
}