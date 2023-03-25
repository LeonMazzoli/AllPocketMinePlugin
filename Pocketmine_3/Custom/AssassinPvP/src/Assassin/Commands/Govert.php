<?php

namespace Assassin\Commands;

use Assassin\Events\KillEvent;
use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class Govert extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("govert", $main);
        $this->setDescription("Téléporte au sumo popo");
        $this->setPermission("govert.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $this->popo($player);
        }
    }

    public function popo($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    self::kitp($player);
                    $lobby = Server::getInstance()->getLevelByName("Lobby");
                    $player->teleport(new Position(1049, 4, 964, $lobby));
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
            }
        });
        $form->setTitle("Kits");
        $form->addButton("§2Sumo");
        $form->sendToPlayer($player);
        return $form;
    }
}