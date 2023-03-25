<?php

namespace Sanction\Commands;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use Sanction\SanctionMain;

class Sanction extends PluginCommand{
    public function __construct(SanctionMain $main)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        parent::__construct("sanction", $main);
        $this->setDescription($config->getNested("sanc.desc"));
        $this->setPermission($config->getNested("sanc.perm"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $data = new Config(SanctionMain::getInstance()->getDataFolder()."data.json",Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if (!$player->hasPermission($config->getNested("sanc.perm"))) return $player->sendMessage($config->getNested("noperm"));
        if (!isset($args[0])) return $player->sendMessage($config->getNested("sanc.noargs"));

        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = $senderr->getName();
        }else $sender = $args[0];

        if ($data->exists(strtolower($sender))){
            $this->form($player, strtolower($sender));
        }else $player->sendMessage($config->getNested("sanc.noplayer"));
        return true;
    }

    public function form($player, $sender){
        $data = new Config(SanctionMain::getInstance()->getDataFolder()."data.json",Config::JSON);
        $config = new Config(SanctionMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($config){
            $result = $data;
            if ($result === null){
                return;
            }
            switch ($result){
                case 0:
                    $player->sendMessage($config->getNested("sanc.form.close"));
                    break;
            }
        });

        $kick = $data->getNested("$sender.kick");
        $mute = $data->getNested("$sender.mute");
        $banip = $data->getNested("$sender.banip");
        $ban = $data->getNested("$sender.ban");

        $content = str_replace([strtolower('{kick}'), strtolower('{player}'), strtolower('{mute}'), strtolower('{banip}'), strtolower('{ban}')], [$kick, $sender, $mute, $banip, $ban], $config->getNested("sanc.form.content"));

        $form->setTitle($config->getNested("sanc.form.title"));
        $form->setContent($content);
        $form->addButton($config->getNested("sanc.form.button"));
        $form->sendToPlayer($player);
        return $form;
    }
}