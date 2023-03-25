<?php

namespace Lobby;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class System extends PluginBase implements Listener{
    public function onEnable()
    {
        $this->getLogger()->info("LobbySystemEvonia on by Digueloulou12");
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info("LobbySystemEvonia off by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()){
            case "lobbyreload":
                $this->getConfig()->reload();
                $player->sendMessage("Vous venez de reload les configs !");
                break;
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if (!$player->getInventory()->contains(Item::get($this->getConfig()->get("item.id")))){
            $item = Item::get($this->getConfig()->get("item.id"));
            $item->setCustomName($this->getConfig()->get("item.name"));
            $player->getInventory()->setItem($this->getConfig()->get("item.slot"), $item);
        }
    }

    public function onBouge(InventoryTransactionEvent $event){
        if ($this->getConfig()->get("transaction.inventory") == false){
            $event->setCancelled(true);
        }
    }

    public function onDrop(PlayerDropItemEvent $event){
        if ($event->getItem()->getId() == Item::get($this->getConfig()->get("item.id"))){
            if ($this->getConfig()->get("drop.item") == false){
                $event->setCancelled(true);
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if ($event->getAction() == 1 or $event->getAction() == 3){
            if ($event->getItem()->getId() == $this->getConfig()->get("item.id")){
                $this->form($player);
            }
        }
    }

    public function form($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($this->getConfig()->get("ouvert.kitpvp") == true){
                        $player->transfer($this->getConfig()->get("ip.kitpvp"), $this->getConfig()->get("port.kitpvp"));
                    }else $player->sendMessage($this->getConfig()->get("message.ferme.kitpvp"));
                    break;
                case 1:
                    if ($this->getConfig()->get("ouvert.faction") == true){
                        $player->transfer($this->getConfig()->get("ip.faction"), $this->getConfig()->get("port.faction"));
                    }else $player->sendMessage($this->getConfig()->get("message.ferme.faction"));
                    break;
            }
        });
        $form->setTitle($this->getConfig()->get("title.ui"));
        $form->setContent($this->getConfig()->get("content.ui"));
        $form->addButton($this->getConfig()->get("kitpvp.ui"));
        $form->addButton($this->getConfig()->get("faction.ui"));
        $form->sendToPlayer($player);
        return $form;
    }
}