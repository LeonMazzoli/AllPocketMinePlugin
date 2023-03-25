<?php

namespace oKit;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use PiggyCustomEnchants\CustomEnchants\CustomEnchants;

class okitMain extends PluginBase implements Listener{
    public $piggyEnchants;
    public function onEnable()
    {
        $this->getLogger()->info("oKit on by Digueloulou12");

        $this->piggyEnchants = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");

        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveResource("config.yml");
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("oKit on by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()){
            case "kit":
                if ($player instanceof Player){
                    $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
                    $inv = $menu->getInventory();
                    $menu->readonly();
                    $menu->setName($this->getConfig()->get("gui.title"));
                    $inv->setItem($this->getConfig()->get("achlis.slot"), Item::get($this->getConfig()->get("achlis.id"))->setCustomName($this->getConfig()->get("achlis.name")));
                    $inv->setItem($this->getConfig()->get("baku.slot"), Item::get($this->getConfig()->get("baku.id"))->setCustomName($this->getConfig()->get("baku.name")));
                    $inv->setItem($this->getConfig()->get("cocatrix.slot"), Item::get($this->getConfig()->get("cocatrix.id"))->setCustomName($this->getConfig()->get("cocatrix.name")));
                    $inv->setItem($this->getConfig()->get("diti.slot"), Item::get($this->getConfig()->get("diti.id"))->setCustomName($this->getConfig()->get("diti.name")));
                    $inv->setItem($this->getConfig()->get("builder.slot"), Item::get($this->getConfig()->get("builder.id"))->setCustomName($this->getConfig()->get("builder.name")));
                    $menu->setListener(function (InvMenuTransaction $action){
                        $item = $action->getItemClicked();
                        $player = $action->getPlayer();
                        if ($item->getId() == $this->getConfig()->get("achlis.id")){
                            if ($player->hasPermission($this->getConfig()->get("achlis.perm"))){
                                foreach ($this->getConfig()->get("achlis.item") as $achlis){
                                    $player->getInventory()->addItem($i = $this->loadItem(...explode(":", $achlis)));
                                }
                                $player->sendMessage($this->getConfig()->get("achlis.message"));
                            }else{
                                $player->sendMessage($this->getConfig()->get("no.perm.achlis"));
                            }
                        }elseif ($item->getId() == $this->getConfig()->get("baku.id")){
                            if ($player->hasPermission($this->getConfig()->get("baku.perm"))){
                                foreach ($this->getConfig()->get("baku.item") as $baku){
                                    $player->getInventory()->addItem($i = $this->loadItem(...explode(":", $baku)));
                                }
                                $player->sendMessage($this->getConfig()->get("baku.message"));
                            }else {
                                $player->sendMessage($this->getConfig()->get("no.perm.baku"));
                            }
                        }elseif ($item->getId() == $this->getConfig()->get("cocatrix.id")){
                            if ($player->hasPermission($this->getConfig()->get("cocatrix.perm"))){
                                foreach ($this->getConfig()->get("cocatrix.item") as $cocatrix){
                                    $player->getInventory()->addItem($i = $this->loadItem(...explode(":", $cocatrix)));
                                }
                                $player->sendMessage($this->getConfig()->get("cocatrix.message"));
                            }else {
                                $player->sendMessage($this->getConfig()->get("no.perm.cocatrix"));
                            }
                        }elseif ($item->getId() == $this->getConfig()->get("diti.id")){
                            if ($player->hasPermission($this->getConfig()->get("diti.perm"))){
                                foreach ($this->getConfig()->get("diti.item") as $diti){
                                    $player->getInventory()->addItem($i = $this->loadItem(...explode(":", $diti)));
                                }
                                $player->sendMessage($this->getConfig()->get("diti.message"));
                            }else {
                                $player->sendMessage($this->getConfig()->get("no.perm.diti"));
                            }
                        }elseif ($item->getId() == $this->getConfig()->get("builder.id")){
                            if ($player->hasPermission($this->getConfig()->get("builder.perm"))){
                                foreach ($this->getConfig()->get("builder.item") as $builder) {
                                    $player->getInventory()->addItem($i = $this->loadItem(...explode(":", $builder)));
                                }
                                $player->sendMessage($this->getConfig()->get("builder.message"));
                            }else {
                                $player->sendMessage($this->getConfig()->get("no.perm.builder"));
                            }
                        }
                        return $action->discard();
                    });
                    $menu->send($player);
                }else{
                    $player->sendMessage("La commande doit être executer en jeu !");
                }
                break;
        }
        return true;
    }

    public function loadItem(int $id = 0, int $damage = 0, int $count = 1, string $name = "default", ...$enchantments) : Item
    {
        $item = Item::get($id, $damage, $count);
        if (strtolower($name) !== "default") {
            $item->setCustomName($name);
        }
        $ench = null;
        foreach($enchantments as $key => $name_level){
            if($key % 2 === 0){
                $ench = Enchantment::getEnchantmentByName((string) $name_level);
                if($ench === null){
                    $ench = CustomEnchants::getEnchantmentByName((string) $name_level);
                }
            }elseif($ench !== null) {
                if ($this->piggyEnchants !== null && $ench instanceof CustomEnchants) {
                    $this->piggyEnchants->addEnchantment($item, $ench->getName(), (int)$name_level);
                } else {
                    $item->addEnchantment(new EnchantmentInstance($ench, (int)$name_level));
                }
            }
        }
        return $item;
    }
}