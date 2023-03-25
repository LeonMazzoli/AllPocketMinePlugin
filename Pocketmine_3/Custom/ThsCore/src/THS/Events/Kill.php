<?php

namespace THS\Events;

use HiroTeam\KDR\database\SqlLite3;
use HiroTeam\KDR\KDRMain;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\API\Play\ArcAPI;
use THS\API\Play\GappleAPI;
use THS\API\Play\HealStickAPI;
use THS\API\Play\KbAPI;
use THS\API\Play\PopoAPI;
use THS\API\Play\SnowAPI;
use THS\Main;

class Kill implements Listener
{
    public function onDamage(EntityDamageEvent $event)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $xp = new Config(Main::getInstance()->getDataFolder() . "xp.json", Config::JSON);

        $victim = $event->getEntity();

        if (!($victim instanceof Player)) return;

        $cause = $victim->getLastDamageCause();

        $damager = null;
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
        } else if ($event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE) {
            $damager = $event->getEntity()->getOwningEntity();
        }

        if (!($damager instanceof Player)) return;

        if ($event->getFinalDamage() >= $victim->getHealth()) {

            $event->setCancelled();

            $victim->setHealth($victim->getMaxHealth());
            $damager->setHealth($damager->getMaxHealth());

            $victim->getInventory()->clearAll();
            $victim->getArmorInventory()->clearAll();

            foreach ($damager->getInventory()->getContents() as $itemclear) {
                $notClear = ["438:16", "438:29", "438:33", "466:0", "378:0"];
                if (!in_array($itemclear->getId() . ":" . $itemclear->getDamage(), $notClear)){
                    $damager->getInventory()->removeItem($itemclear);
                }
            }
            $damager->getArmorInventory()->clearAll();

            $world = $damager->getLevel()->getName();
            switch ($world) {
                case "Arc":
                    if ($damager->getInventory()->contains(Item::get(Item::ARROW, 0, 1))) {
                        ArcAPI::kit($damager);
                    } else SnowAPI::kit($damager);
                    Server::getInstance()->getCommandMap()->dispatch($victim, 'hub');
                    foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
                        $namee = strtolower($sender->getName());
                        if ($language->get($namee) === "fr"){
                            $sender->sendMessage(Main::$prefix."Le joueur§a {$victim->getName()} §fa été tué par§a {$damager->getName()} §fdans le jeu Arc/Snow !");
                        }else $sender->sendMessage(Main::$prefix."The player§a {$victim->getName()} §fwas killed by§a {$damager->getName()} §fin the Arc / Snow game!");
                    }
                    break;
                case "Gapple":
                    GappleAPI::startGapple($victim);
                    switch (GappleAPI::$kit[$damager->getName()]){
                        case "player":
                            GappleAPI::player($damager);
                            break;
                        case "vip":
                            GappleAPI::vip($damager);
                            break;
                        case "tatar":
                            GappleAPI::tatar($damager);
                            break;
                        case "legende":
                            GappleAPI::legende($damager);
                            break;
                        case "champion":
                            GappleAPI::champion($damager);
                            break;
                        case "patrones":
                            GappleAPI::patrones($damager);
                            break;
                        case "supreme":
                            GappleAPI::supreme($damager);
                            break;
                    }

                    MoneyAPI::addMoney($damager, 2);
                    MoneyAPI::removeMoney($victim, 1);

                    LanguageAPI::sendMessage($damager,"Vous avez gagné§a 2§f$ en faisant un kill !", "You won §a2§f$ by killing!");
                    LanguageAPI::sendMessage($victim, "Vous venez de perdre§a 1§f$ en mourant !", "You just lost §a1§f$ dying!");

                    foreach (Server::getInstance()->getOnlinePlayers() as $sender){
                        $nameee = strtolower($sender->getName());
                        if ($language->get($nameee) === "fr"){
                            $sender->sendMessage(Main::$prefix."Le joueur§a {$victim->getName()} §fà été tué par §a{$damager->getName()}§f dans le jeu Gapple !");
                        }else $sender->sendMessage(Main::$prefix."The player§a {$victim->getName()} §fwas killed by§a {$damager->getName()}§f in the game Gapple!");
                    }
                    KDRMain::getInstance()->getProvider()->addAmount($victim->getName(), 1, SqlLite3::OPTION_DEATH);
                    KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
                    break;
                case "KB":
                    KbAPI::startKB($victim);
                    $damager->getInventory()->clearAll();
                    $damager->getInventory()->addItem(Item::get(Item::BLAZE_ROD, 0, 1));
                    $bow = Item::get(Item::BOW, 0, 1);
                    $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
                    $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PUNCH), 7));
                    $damager->getInventory()->addItem($bow);
                    $damager->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
                    break;
                case "Heal":
                    $damager->getInventory()->clearAll();
                    HealStickAPI::kit($damager);
                    HealStickAPI::startHeal($victim);

                    MoneyAPI::addMoney($damager, 2);
                    MoneyAPI::removeMoney($victim, 1);

                    LanguageAPI::sendMessage($damager,"Vous avez gagné§a 2§f$ en faisant un kill !", "You won §a2§f$ by killing!");
                    LanguageAPI::sendMessage($victim, "Vous venez de perdre§a 1§f$ en mourant !", "You just lost §a1§f$ dying!");

                    foreach (Server::getInstance()->getOnlinePlayers() as $sender){
                        $nameee = strtolower($sender->getName());
                        if ($language->get($nameee) === "fr"){
                            $sender->sendMessage(Main::$prefix."Le joueur§a {$victim->getName()} §fà été tué par §a{$damager->getName()}§f dans le jeu HealSick !");
                        }else $sender->sendMessage(Main::$prefix."The player§a {$victim->getName()} §fwas killed by§a {$damager->getName()}§f in the game HealSick!");
                    }
                    KDRMain::getInstance()->getProvider()->addAmount($victim->getName(), 1, SqlLite3::OPTION_DEATH);
                    KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
                    break;
                case "Popo":
                    PopoAPI::start($victim);
                    switch (PopoAPI::$kit[$damager->getName()]){
                        case "player":
                            PopoAPI::player($damager);
                            break;
                        case "vip":
                            PopoAPI::vip($damager);
                            break;
                        case "tatar":
                            PopoAPI::tatar($damager);
                            break;
                        case "legende":
                            PopoAPI::legende($damager);
                            break;
                        case "champion":
                            PopoAPI::champion($damager);
                            break;
                        case "patrones":
                            PopoAPI::patrones($damager);
                            break;
                        case "supreme":
                            PopoAPI::supreme($damager);
                            break;
                    }

                    MoneyAPI::addMoney($damager, 2);
                    MoneyAPI::removeMoney($victim, 1);

                    LanguageAPI::sendMessage($damager,"Vous avez gagné§a 2§f$ en faisant un kill !", "You won §a2§f$ by killing!");
                    LanguageAPI::sendMessage($victim, "Vous venez de perdre§a 1§f$ en mourant !", "You just lost §a1§f$ dying!");

                    foreach (Server::getInstance()->getOnlinePlayers() as $sender){
                        $nameee = strtolower($sender->getName());
                        if ($language->get($nameee) === "fr"){
                            $sender->sendMessage(Main::$prefix."Le joueur§a {$victim->getName()} §fà été tué par §a{$damager->getName()}§f dans le jeu NoDebuff !");
                        }else $sender->sendMessage(Main::$prefix."The player§a {$victim->getName()} §fwas killed by§a {$damager->getName()}§f in the game NoDebuff!");
                    }
                    KDRMain::getInstance()->getProvider()->addAmount($victim->getName(), 1, SqlLite3::OPTION_DEATH);
                    KDRMain::getInstance()->getProvider()->addAmount($damager->getName(), 1, SqlLite3::OPTION_KILL);
                    break;
            }
        }
    }
}