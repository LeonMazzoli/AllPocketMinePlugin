<?php

namespace Assassin\Commands;

use Assassin\Main;
use Assassin\ModePvP\KitSumo;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\Server;

class Sumo extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("sumo",$main);
        $this->setDescription("Ouvre l'interface des sumo");
        $this->setPermission("sumo.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $monde = Server::getInstance()->getLevelByName("SUMO");
        }
    }

    public static function sumo($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    KitSumo::sumopopo($player);
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
                case 1:
                    KitSumo::sumoarc($player);
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
                case 2:
                    KitSumo::sumobasique($player);
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
                case 3:
                    KitSumo::kitsnow($player);
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
            }
        });
        $form->setTitle("Sumo");
        $form->setContent("Choisie le kit sumo que tu désire:");
        $form->addButton("§2Kit Popo");
        $form->addButton("§4Kit Arc");
        $form->addButton("§eKit basique");
        $form->addButton("§1Kit SnowBall");
        $form->sendToPlayer($player);
        return $form;
    }
}