<?php

namespace THS\Commands\Sanction;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Invsee extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("invsee", $main);
        $this->setPermission("invsee.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) {
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
            return;
        }

        if (!$player->hasPermission("invsee.use")) {
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!isset($args[0])) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null) {
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas connecté !", "The indicated player is not connected!");
            return;
        }

        $target = Server::getInstance()->getPlayer($args[0]);
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $glass = Item::get(20, 0, 1);
        $glass->setCustomName("§c---");
        $menu->getInventory()->setItem(36, $glass);
        $menu->getInventory()->setItem(37, $glass);
        $menu->getInventory()->setItem(38, $glass);
        $menu->getInventory()->setItem(39, $glass);
        $menu->getInventory()->setItem(40, $glass);
        $menu->getInventory()->setItem(41, $glass);
        $menu->getInventory()->setItem(42, $glass);
        $menu->getInventory()->setItem(43, $glass);
        $menu->getInventory()->setItem(44, $glass);
        $glass->setCustomName("§aCasque ->");
        $menu->getInventory()->setItem(45, $glass);
        $glass->setCustomName("§a<- Casque | Plastron ->");
        $menu->getInventory()->setItem(47, $glass);
        $glass->setCustomName("§a<- Plastron | Pantalon ->");
        $menu->getInventory()->setItem(49, $glass);
        $glass->setCustomName("§a<- Pantalon | Bottes ->");
        $menu->getInventory()->setItem(51, $glass);
        $glass->setCustomName("§a<- Bottes");
        $menu->getInventory()->setItem(53, $glass);
        foreach($target->getInventory()->getContents() as $value => $item){
            $menu->getInventory()->setItem($value, $item);
        }
        $menu->getInventory()->setItem(46, $target->getArmorInventory()->getHelmet());
        $menu->getInventory()->setItem(48, $target->getArmorInventory()->getChestplate());
        $menu->getInventory()->setItem(50, $target->getArmorInventory()->getLeggings());
        $menu->getInventory()->setItem(52, $target->getArmorInventory()->getBoots());
        $menu->setName("Inventaire de §a{$target->getName()}");
        $menu->setListener(InvMenu::readonly(function (InvMenuTransaction $action) use ($player, $target): void {
            if ($player->getInventory()->canAddItem($action->getItemClicked())){
                $player->getInventory()->addItem($action->getItemClicked());
                $target->getEnderChestInventory()->removeItem($action->getItemClicked());
            }
        }));
        $menu->send($player);
    }
}