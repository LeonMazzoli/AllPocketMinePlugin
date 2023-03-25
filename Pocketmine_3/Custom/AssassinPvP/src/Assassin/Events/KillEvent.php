<?php

namespace Assassin\Events;

use Assassin\Main;
use Assassin\ModePvP\KitSumo;
use Assassin\ModePvP\Mode;
use HiroTeam\KDR\database\SqlLite3;
use HiroTeam\KDR\KDRMain;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class KillEvent implements Listener
{
    public static $sumo = [];

    public function onDamage(EntityDamageEvent $event)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);

        $victim = $event->getEntity();
        if(!($victim instanceof Player)) return;
        $cause = $victim->getLastDamageCause();
        $damager = null;
        if($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
        } else if ($event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE){
            $damager = $event->getEntity()->getOwningEntity();
        }
        if(!($damager instanceof Player)) return;
        if ($event->getFinalDamage() >= $victim->getHealth()) {

            $event->setCancelled();
            $victim->setFood(20);
            $victim->setHealth($victim->getMaxHealth());
            $victim->getInventory()->clearAll();
            $victim->getArmorInventory()->clearAll();
            $damager->setHealth($damager->getMaxHealth());
            $damager->setFood($damager->getMaxFood());
            if ($victim->getLevel()->getName() == "arcpvp") {
                Mode::tparc($victim);
                Mode::arc($victim);
                Mode::arc($damager);
                Server::getInstance()->broadcastMessage(Main::$prefix . "§a" . $victim->getName() . " §fa été assassiné par§a " . $damager->getName());
            } elseif ($victim->getLevel()->getName() === "snowpvp") {
                Mode::tpsnow($victim);
                Mode::snow($victim);
                Mode::snow($damager);
                Server::getInstance()->broadcastMessage(Main::$prefix . "§a" . $victim->getName() . " §fa été assassiné par§a " . $damager->getName());
            } elseif ($victim->getLevel()->getName() === "BuildAssassin") {
                $world = Server::getInstance()->getLevelByName("BuildAssassin");
                $victim->teleport($world->getSafeSpawn());
                $victim->getInventory()->clearAll();
                $item = Item::get(Item::BLAZE_ROD, 0, 1);
                $bow = Item::get(Item::BOW, 0, 1);
                $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PUNCH), 7));
                $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
                $item->setCustomName("Excalibur");
                $victim->getInventory()->addItem($item);
                $victim->getInventory()->addItem($bow);
                $victim->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
            } elseif ($victim->getLevel()->getName() === "ArenePvP") {
                $monde = Server::getInstance()->getLevelByName("ArenePvP");
                $victim->teleport(new Position(-28, 211, 20, $monde));
                $victim->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
                if (Kits::$kit[$damager->getName()] === "joueur") {
                    Kits::GappleJoueur($damager);
                } elseif (Kits::$kit[$damager->getName()] === "vip") {
                    Kits::GappleVip($damager);
                } elseif (Kits::$kit[$damager->getName()] === "vipp") {
                    Kits::GappleVipPlus($damager);
                } elseif (Kits::$kit[$damager->getName()] === "tatar") {
                    Kits::GappleTatar($damager);
                } elseif (Kits::$kit[$damager->getName()] === "legende") {
                    Kits::GappleLegende($damager);
                } elseif (Kits::$kit[$damager->getName()] === "champion") {
                    Kits::GappleChampion($damager);
                } elseif (Kits::$kit[$damager->getName()] === "el") {
                    Kits::GappleEl($damager);
                } else {
                    Kits::GappleJoueur($damager);
                }
                Server::getInstance()->broadcastMessage(Main::$prefix . "§a" . $victim->getName() . " §fa été assassiné par§a " . $damager->getName());
                $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                $eco->addMoney($damager, 1);
                KDRMain::getInstance()->getProvider()->addAmount($victim->getName(), 1, SqlLite3::OPTION_DEATH);
                KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
            } elseif ($victim->getLevel()->getName() === "AreneJap") {
                $monde = Server::getInstance()->getLevelByName("AreneJap");
                $victim->teleport(new Position(169, 116, -94, $monde));
                $victim->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
                if (Kits::$kit[$damager->getName()] === "joueur") {
                    Kits::popoJoueur($damager);
                } elseif (Kits::$kit[$damager->getName()] === "vip") {
                    Kits::popoVip($damager);
                } elseif (Kits::$kit[$damager->getName()] === "vipp") {
                    Kits::popoVipPlus($damager);
                } elseif (Kits::$kit[$damager->getName()] === "tatar") {
                    Kits::popoTatar($damager);
                } elseif (Kits::$kit[$damager->getName()] === "legende") {
                    Kits::popoLegende($damager);
                } elseif (Kits::$kit[$damager->getName()] === "champion") {
                    Kits::popoChampion($damager);
                } elseif (Kits::$kit[$damager->getName()] === "el") {
                    Kits::popoEl($damager);
                } else {
                    Kits::popoJoueur($damager);
                }
                Server::getInstance()->broadcastMessage(Main::$prefix . "§a" . $victim->getName() . " §fa été assassiné par§a " . $damager->getName());
                $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                $eco->addMoney($damager, 1);
                KDRMain::getInstance()->getProvider()->addAmount($victim->getName(), 1, SqlLite3::OPTION_DEATH);
                KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
            } elseif ($victim->getLevel()->getName() === "SUMO") {
                if (empty(self::$sumo[$damager->getName()])) return;
                $monde = Server::getInstance()->getLevelByName("SUMO");
                $victim->teleport(new Position(362, 2, 61, $monde));
                $victim->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
                if (self::$sumo[$damager->getName()] === "arc") {
                    KitSumo::sumoarc($damager);
                } elseif (self::$sumo[$damager->getName()] === "snow") {
                    KitSumo::kitsnow($damager);
                } elseif (self::$sumo[$damager->getName()] === "basique") {
                    KitSumo::sumobasique($damager);
                } elseif (self::$sumo[$damager->getName()] === "popo") {
                    KitSumo::sumopopo($damager);
                }
            }
        }
    }
}