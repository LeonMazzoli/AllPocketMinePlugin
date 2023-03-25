<?php

namespace THS\Commands\Sanction;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class EnderInvsee extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("enderinvsee", $main);
        $this->setPermission("enderinvsee.use");
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

        $player->setNameTagVisible(true);
        $target = Server::getInstance()->getPlayer($args[0]);
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        foreach($target->getEnderChestInventory()->getContents() as $value => $item){
            $menu->getInventory()->setItem($value, $item);
        }
        $menu->setName("Enderchest de §a{$target->getName()}");
        $menu->setListener(InvMenu::readonly(function (InvMenuTransaction $action) use ($player, $target): void {
            if ($player->getInventory()->canAddItem($action->getItemClicked())){
                $player->getInventory()->addItem($action->getItemClicked());
                $target->getEnderChestInventory()->removeItem($action->getItemClicked());
            }
        }));
        $menu->send($player);
    }
}