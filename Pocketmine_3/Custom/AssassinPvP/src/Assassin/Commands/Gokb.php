<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class Gokb extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("gokb", $main);
        $this->setDescription("Téléporte dans le jeu knocback");;
        $this->setPermission("gokb.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $world = Server::getInstance()->getLevelByName("BuildAssassin");
            $player->teleport($world->getSafeSpawn());
            $player->getInventory()->clearAll();
            $item = Item::get(Item::BLAZE_ROD, 0, 1);
            $bow = Item::get(Item::BOW, 0, 1);
            $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PUNCH), 7));
            $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
            $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5000));
            $item->setCustomName("Excalibur");
            $player->getInventory()->addItem($item);
            $player->getInventory()->addItem($bow);
            $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
            $player->setHealth(20);
            $player->setFood(20);
            $player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 2, false));
        }
    }
}