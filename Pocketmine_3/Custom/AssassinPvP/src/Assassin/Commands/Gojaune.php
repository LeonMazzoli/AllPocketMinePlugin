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

class Gojaune extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("gojaune", $main);
        $this->setDescription("Téléporte dans la warzone main");
        $this->setPermission("gojaune.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $this->kit($player);
        }
    }

    public function kit($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    self::kitj($player);
                    $lobby = Server::getInstance()->getLevelByName("Lobby");
                    $player->teleport(new Position(991, 4, 1022, $lobby));
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10000, 0, false));
                    break;
            }
        });
        $form->setTitle("Kits");
        $form->addButton("§eSumo");
        $form->sendToPlayer($player);
        return $form;
    }
}