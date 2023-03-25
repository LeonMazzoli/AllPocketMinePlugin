<?php

namespace Admin\Event;

use Admin\Command\AdminMenu;
use Admin\Form\AdminForm;
use Admin\Menu;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;

class AdminEvent implements Listener{
    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!empty(AdminMenu::$adminmenu[$player->getName()])){
            $itemm = explode(":", Menu::getInstance()->getConfigValue("item"));
            if ($item->getId() === $itemm[0] and $item->getDamage() === $itemm[1]){
                if ($event->getAction() == 1 or $event->getAction() == 3){
                    if ($player->isSneaking()){
                        $players = Server::getInstance()->getOnlinePlayers();
                        $random = $players[array_rand($players)];
                        $player->teleport($random);
                        $player->sendMessage(str_replace(strtolower("{player}"), $random->getName(), Menu::getInstance()->getConfigValue("randomtp")));
                    }else AdminForm::AdminPlayerTp($player);
                }else{
                    if ($player->getGamemode() != 3){
                        $event->setCancelled(true);
                        $player->setGamemode(3);
                    }else{
                        $event->setCancelled(true);
                        $player->setGamemode(0);
                    }
                }
            }
        }
    }

    public function onTap(EntityDamageByEntityEvent $event){
        $sender = $event->getEntity();
        $player = $event->getDamager();

        if (($player instanceof Player) and ($sender instanceof Player)){
            if (!empty(AdminMenu::$adminmenu[$player->getName()])){
                $event->setCancelled(true);
                AdminForm::ActionPlayer($player, $sender);
            }
        }
    }

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        if (!empty(AdminForm::$mute[$player->getName()])){
            if (AdminForm::$mute[$player->getName()] > time()){
                $event->setCancelled(true);
                $player->sendMessage(Menu::getInstance()->getConfigValue("mutep"));
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event){
        if (isset(AdminMenu::$adminmenu[$event->getPlayer()->getName()])){
            $event->getPlayer()->getInventory()->clearAll();
            $event->getPlayer()->getInventory()->setContents(AdminMenu::$adminmenu[$event->getPlayer()->getName()]);
            unset(AdminMenu::$adminmenu[$event->getPlayer()->getName()]);
        }
    }
}