<?php

namespace Assassin\Events;

use Assassin\ModePvP\Mode;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class Excalibur implements Listener{
    public function Damage(EntityDamageByEntityEvent $event)
    {
        $victim = $event->getEntity();
        $player = $event->getDamager();
        if ($victim instanceof Player && $player instanceof Player) {
            $hand = $player->getInventory()->getItemInHand();
            if ($hand->getId() === 369) {
                $kb = $event->getKnockBack();
                $event->setKnockBack($kb * floatval(7));
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @priority LOWEST
     */
    public function onHit(EntityDamageEvent $event)
    {

        if($event->getCause() !== EntityDamageEvent::CAUSE_PROJECTILE) return;
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ("BuildAssassin" === $player->getLevel()->getName()) {
                $player->setHealth($player->getMaxHealth());
            }elseif ($player->getLevel()->getName() === "snowpvp"){
                $event->setBaseDamage(2);
            }elseif ($player->getLevel()->getName() === "Lobby"){
                $player->setHealth($player->getHealth() + 1);
            }
        }
    }

    public function onEvent(ProjectileLaunchEvent $event){
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ($event->getEntity()->get >= $player->getHealth()) {
                $cause = $event->getEntity()->getLastDamageCause();
                if ($cause instanceof EntityDamageByEntityEvent or $cause instanceof ProjectileHitEntityEvent) {
                    $damager = $cause->getDamager();
                    if ($damager instanceof Player) {
                        $event->setCancelled();
                        $player->setHealth($player->getMaxHealth());
                        $player->getInventory()->clearAll();
                        $player->getArmorInventory()->clearAll();
                        if ($player->getLevel()->getName() == "arcpvp") {
                            $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                            $eco->addMoney($damager, 1);
                            Mode::tparc($player);
                            Mode::arc($player);
                            Mode::arc($player);
                        } elseif ($player->getLevel()->getName() === "snowpvp") {
                            $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                            $eco->addMoney($damager, 1);
                            Mode::tpsnow($player);
                            Mode::snow($player);
                            Mode::snow($damager);
                        } elseif ($player->getLevel()->getName() === "BuildAssassin") {
                            $world = Server::getInstance()->getLevelByName("BuildAssassin");
                            $player->teleport($world->getSafeSpawn());
                            $player->getInventory()->clearAll();
                            $item = Item::get(Item::BLAZE_ROD, 0, 1);
                            $bow = Item::get(Item::BOW, 0, 1);
                            $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PUNCH), 7));
                            $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
                            $item->setCustomName("Excalibur");
                            $player->getInventory()->addItem($item);
                            $player->getInventory()->addItem($bow);
                            $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
                            $player->setFood(20);
                            $player->setHealth($player->getMaxHealth());
                        }elseif ($player->getLevel()->getName() === "ArenePvP"){
                            $player->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
                        }elseif ($player->getLevel()->getName() === "ArenePvP2"){
                            $player->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
                        }
                        KDRMain::getInstance()->getProvider()->addAmount($player->getName(), 1, SqlLite3::OPTION_DEATH);
                        KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
                    }
                }
            }
        }
    }

    public function onDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if ($player->getLevel()->getName() === "BuildAssassin"){
            if ($player->getY() <= 50){
                if ($player instanceof Player){
                    $player->getInventory()->clearAll();
                    $player->getArmorInventory()->clearAll();
                    $player->teleport($player->getLevel()->getSafeSpawn());
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
    }
}