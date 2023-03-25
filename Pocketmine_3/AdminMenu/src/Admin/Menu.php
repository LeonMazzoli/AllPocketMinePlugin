<?php

namespace Admin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Menu extends PluginBase implements Listener{
    public $admin = [];
    public $muted = [];
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveResource("config.yml");
        }
        $this->getLogger()->info("AdminMenu on by Digueloulou12");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info("AdminMenu off by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()){
            case "adminmenu":
                if (isset($args[0])){
                    if ($player->hasPermission("adminmenu.user.use")){
                        if (Server::getInstance()->getPlayer($args[0]) != null){
                            $sender = Server::getInstance()->getPlayer($args[0]);
                            if(!isset($this->admin[$sender->getName()])) {
                                $sender->getInventory()->clearAll();
                                $stick = Item::get(Item::STICK);
                                $kb = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::KNOCKBACK), $this->getConfig()->get("stick.kb"));
                                $stick->addEnchantment($kb);
                                $stick->setCustomName($this->getConfig()->get("stick.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("stick.slot"), $stick);
                                $vanish = Item::get($this->getConfig()->get("vanish.id"));
                                $vanish->setCustomName($this->getConfig()->get("vanish.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("vanish.slot"), $vanish);
                                $freeze = Item::get($this->getConfig()->get("freeze.id"));
                                $freeze->setCustomName($this->getConfig()->get("freeze.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("freeze.slot"), $freeze);
                                $ban = Item::get($this->getConfig()->get("ban.id"));
                                $ban->setCustomName($this->getConfig()->get("ban.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("ban.slot"), $ban);
                                $kick = Item::get($this->getConfig()->get("kick.id"));
                                $kick->setCustomName($this->getConfig()->get("kick.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("kick.slot"), $kick);
                                $ping = Item::get($this->getConfig()->get("ping.id"));
                                $ping->setCustomName($this->getConfig()->get("ping.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("ping.slot"), $ping);
                                $mute = Item::get($this->getConfig()->get("mute.id"));
                                $mute->setCustomName($this->getConfig()->get("mute.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("mute.slot"), $mute);
                                $tp = Item::get($this->getConfig()->get("tp.id"));
                                $tp->setCustomName($this->getConfig()->get("tp.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("tp.slot"), $tp);
                                $kill = Item::get($this->getConfig()->get("kill.id"));
                                $kill->setCustomName($this->getConfig()->get("kill.name"));
                                $sender->getInventory()->setItem($this->getConfig()->get("kill.slot"), $kill);
                                $custommessage = $this->getConfig()->get("adminmenu.user.on");
                                $message = str_replace("{player}", $player->getName(), $custommessage);
                                $sender->sendMessage($message);
                                $cmess = $this->getConfig()->get("adminmenu.user.on.player");
                                $mess = str_replace("{player}", $sender->getName(), $cmess);
                                $player->sendMessage($mess);
                                $this->admin[$sender->getName()] = $sender;
                            } else {
                                $sender->getInventory()->clearAll();
                                $cme = $this->getConfig()->get("adminmenu.user.off");
                                $me = str_replace("{player}", $player->getName(), $cme);
                                $sender->sendMessage($me);
                                $cmes = $this->getConfig()->get("adminmenu.user.off.player");
                                $mes = str_replace("{player}", $sender->getName(), $cmes);
                                $player->sendMessage($mes);
                                unset($this->admin[$sender->getName()]);
                            }
                        }else{
                            $player->sendMessage($this->getConfig()->get("player.null"));
                        }
                    }else{
                        $player->sendMessage($this->getConfig()->get("no.perm"));
                    }
                }else{
                    if ($player instanceof Player){
                        if ($player->hasPermission("adminmenu.use")){
                            if (!isset($this->admin[$player->getName()])){
                                $player->getInventory()->clearAll();
                                $stick = Item::get(Item::STICK);
                                $kb = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::KNOCKBACK), $this->getConfig()->get("stick.kb"));
                                $stick->addEnchantment($kb);
                                $stick->setCustomName($this->getConfig()->get("stick.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("stick.slot"), $stick);
                                $vanish = Item::get($this->getConfig()->get("vanish.id"));
                                $vanish->setCustomName($this->getConfig()->get("vanish.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("vanish.slot"), $vanish);
                                $freeze = Item::get($this->getConfig()->get("freeze.id"));
                                $freeze->setCustomName($this->getConfig()->get("freeze.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("freeze.slot"), $freeze);
                                $ban = Item::get($this->getConfig()->get("ban.id"));
                                $ban->setCustomName($this->getConfig()->get("ban.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("ban.slot"), $ban);
                                $kick = Item::get($this->getConfig()->get("kick.id"));
                                $kick->setCustomName($this->getConfig()->get("kick.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("kick.slot"), $kick);
                                $ping = Item::get($this->getConfig()->get("ping.id"));
                                $ping->setCustomName($this->getConfig()->get("ping.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("ping.slot"), $ping);
                                $mute = Item::get($this->getConfig()->get("mute.id"));
                                $mute->setCustomName($this->getConfig()->get("mute.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("mute.slot"), $mute);
                                $tp = Item::get($this->getConfig()->get("tp.id"));
                                $tp->setCustomName($this->getConfig()->get("tp.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("tp.slot"), $tp);
                                $kill = Item::get($this->getConfig()->get("kill.id"));
                                $kill->setCustomName($this->getConfig()->get("kill.name"));
                                $player->getInventory()->setItem($this->getConfig()->get("kill.slot"), $kill);
                                $player->sendMessage($this->getConfig()->get("adminmenu.on"));
                                $this->admin[$player->getName()] = $player;
                            } else {
                                $player->getInventory()->clearAll();
                                $player->sendMessage($this->getConfig()->get("adminmenu.off"));
                                unset($this->admin[$player->getName()]);
                                if ($player->isInvisible()){
                                    $player->setInvisible(false);
                                }
                            }
                        }else{
                            $player->sendMessage($this->getConfig()->get("no.perm"));
                        }
                    }else{
                        $player->sendMessage($this->getConfig()->get("no.player"));
                    }
                }
                break;
        }
        return true;
    }

    public function onDrop(PlayerDropItemEvent $event){
        $player = $event->getPlayer();
        if(!empty($this->admin[$player->getName()])) {
            $event->setCancelled(true);
        }
    }

    public function onBouge(InventoryTransactionEvent $event){
        $player = $event->getTransaction()->getSource()->getPlayer();
        if(!empty($this->admin[$player->getName()])) {
            $event->setCancelled(true);
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!empty($this->admin[$player->getName()])) {
            if ($event->getAction() == 1 or $event->getAction() == 3) {
                if ($item->getId() == $this->getConfig()->get("vanish.id")) {
                    if ($player->getGamemode() != 3) {
                        $event->setCancelled(true);
                        $player->setGamemode(3);
                        $player->sendMessage($this->getConfig()->get("vanish.message.on"));
                    } else {
                        $event->setCancelled(true);
                        $player->setGamemode(0);
                        $player->sendMessage($this->getConfig()->get("vanish.message.off"));
                    }
                }elseif ($item->getId() == $this->getConfig()->get("freeze.id")){
                    $event->setCancelled(true);
                    $this->Freeze($player);
                }elseif ($item->getId() == $this->getConfig()->get("ban.id")){
                    $event->setCancelled(true);
                    $this->entreBan($player);
                }elseif ($item->getId() == $this->getConfig()->get("kick.id")){
                    $event->setCancelled(true);
                    $this->Kick($player);
                }elseif ($item->getId() == $this->getConfig()->get("ping.id")){
                    $event->setCancelled(true);
                    $this->Ping($player);
                }elseif ($item->getId() == $this->getConfig()->get("mute.id")){
                    $event->setCancelled(true);
                    $this->Mute($player);
                }elseif ($item->getId() == $this->getConfig()->get("kill.id")){
                    $event->setCancelled(true);
                    $this->Kill($player);
                }elseif ($item->getId() == $this->getConfig()->get("tp.id")){
                    if ($player->isSneaking()){
                        $event->setCancelled(true);
                        $players = Server::getInstance()->getOnlinePlayers();
                        $random = $players[array_rand($players)];
                        $player->teleport($random);
                        $player->sendMessage("Vous vous etes tp !");
                    }else{
                        $event->setCancelled(true);
                        $this->Player($player);
                    }
                }
            }
        }
    }

    public function Ping($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (Server::getInstance()->getPlayer($data[1]) != null) {
                $sender = Server::getInstance()->getPlayer($data[1]);
                $ping = $sender->getPing();
                $custommessage = $this->getConfig()->get("ping.message.serveur");
                $message = str_replace("{ping}", $ping, $custommessage);
                Server::getInstance()->broadcastMessage($message);
            } else {
                $player->sendMessage($this->getConfig()->get("ping.player.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("ping.title"));
        $form->addLabel($this->getConfig()->get("ping.content"));
        $form->addInput($this->getConfig()->get("ping.input"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function Kill($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (Server::getInstance()->getPlayer($data[1]) != null) {
                if ($data[2] != null){
                    $sender = Server::getInstance()->getPlayer($data[1]);
                    $sender->kill();
                    $custommessage = $this->getConfig()->get("kill.message.player");
                    $message = str_replace("{player}", $sender->getName(), $custommessage);
                    $player->sendMessage($message);
                    $custommessag = $this->getConfig()->get("kill.serveur.message");
                    $messag = str_replace("{player}", $sender->getName(), $custommessag);
                    Server::getInstance()->broadcastMessage($messag);
                    $custommessa = $this->getConfig()->get("kill.message");
                    $messa = str_replace("{player}", $player->getName(), $custommessa);
                    $sender->sendMessage($messa);
                }else{
                    $player->sendMessage($this->getConfig()->get("kill.raison.no"));
                }
            } else {
                $player->sendMessage($this->getConfig()->get("kill.player.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("kill.title"));
        $form->addLabel($this->getConfig()->get("kill.content"));
        $form->addInput($this->getConfig()->get("kill.input"));
        $form->addInput($this->getConfig()->get("kill.inputr"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function Kick($player)
    {

        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (Server::getInstance()->getPlayer($data[1]) != null) {
                $sender = Server::getInstance()->getPlayer($data[1]);
                $custommessag = $this->getConfig()->get("kick.message");
                $messag = str_replace("{raison}", $data[2], $custommessag);
                $sender->kick($messag);
                $custommessa = $this->getConfig()->get("kick.message");
                $messa = str_replace("{player}", $sender->getName(), $custommessa);
                Server::getInstance()->broadcastMessage($messa);
                $custommessage = $this->getConfig()->get("kick.message");
                $message = str_replace("{player}", $sender->getName(), $custommessage);
                $player->sendMessage($message);
            } else {
                $player->sendMessage($this->getConfig()->get("kick.player.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("kick.title"));
        $form->addLabel($this->getConfig()->get("kick.content"));
        $form->addInput($this->getConfig()->get("kick.input"));
        $form->addInput($this->getConfig()->get("kick.inputr"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function Freeze($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (Server::getInstance()->getPlayer($data[1]) != null) {
                $sender = Server::getInstance()->getPlayer($data[1]);
                if ($sender->isImmobile()){
                    $sender->setImmobile(false);
                    $sender->sendMessage($this->getConfig()->get("unfreeze.message"));
                    $player->sendMessage($this->getConfig()->get("unfreeze.message.player"));
                }else{
                    $sender->setImmobile(true);
                    $sender->sendMessage($this->getConfig()->get("freeze.message"));
                    $player->sendMessage($this->getConfig()->get("freeze.message.player"));
                }
            } else {
                $player->sendMessage($this->getConfig()->get("freeze.player.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("freeze.title"));
        $form->addLabel($this->getConfig()->get("freeze.content"));
        $form->addInput($this->getConfig()->get("freeze.input"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function Ban($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if ($data[3] != null) {
                if ($data[1] != null) {
                    if ($data[2] != null) {
                        if (Server::getInstance()->getPlayer($data[1])) {
                            $config = new Config($this->getDataFolder() . "Ban.yml", Config::YAML);
                            $sender = Server::getInstance()->getPlayer($data[1]);
                                $temp = substr("$data[3]", -1);
                                $temps = substr("$data[3]", 0, -1);
                                if ($temp === "m" or $temp === "h" or $temp === "d" and is_numeric($temps)) {
                                    switch ($temp) {
                                        case "d":
                                            $t = $temps * 60 * 60 * 24;
                                            break;
                                        case "h":
                                            $t = $temps * 60 * 60;
                                            break;
                                        case "m":
                                            $t = $temps * 60;
                                            break;
                                    }
                                    $info = [
                                        "joueur" => $sender->getName(),
                                        "temps" => time() + $t,
                                        "raison" => $data[2]
                                    ];
                                    $config->setNested($sender->getName(), $info);
                                    $config->save();
                                    $name = $sender->getName();
                                    $pname = $sender->getName();
                                    $sender->close("$name a été banni(e) par $name pour $data[2]", "Tu as été ban : par $name raison $data[2] pendant $data[3]");
                                    $custommes = $this->getConfig()->get("ban.message.player");
                                    $mes = str_replace("{player}", $sender->getName(), $custommes);
                                    $player->sendMessage($mes);
                                    $custommess = $this->getConfig()->get("ban.serveur.message");
                                    $mess = str_replace("{player}", $sender->getName(), $custommess);
                                    Server::getInstance()->broadcastMessage($mess);
                                }else{
                                    $player->sendMessage($this->getConfig()->get("ban.temps.no"));
                                }
                            } else {
                                $player->sendMessage($this->getConfig()->get("ban.player.no"));
                            }
                    } else {
                        $player->sendMessage($this->getConfig()->get("ban.raison"));
                    }
                } else {
                    $player->sendMessage($this->getConfig()->get("ban.player.no"));
                }
            }else{
                $player->sendMessage($this->getConfig()->get("ban.temps.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("ban.title"));
        $form->addLabel($this->getConfig()->get("ban.content"));
        $form->addInput($this->getConfig()->get("ban.input"));
        $form->addInput($this->getConfig()->get("ban.inputr"));
        $form->addInput($this->getConfig()->get("ban.inputt"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function Mute($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (Server::getInstance()->getPlayer($data[1]) != null) {
                $sender = Server::getInstance()->getPlayer($data[1]);
                if (is_numeric($data[2])){
                    $num = $data[2] * 60;
                    if (empty($this->muted[$sender->getName()])){
                        if ($data[3] != null){
                            $this->muted[$sender->getName()] = time() + $num;
                            $custommessa = $this->getConfig()->get("mute.message.player");
                            $messa = str_replace("{raison}", $data[3], $custommessa);
                            $player->sendMessage($messa);
                            $custommess = $this->getConfig()->get("mute.message");
                            $mess = str_replace("{temps}", $data[2], $custommess);
                            $sender->sendMessage($mess);
                            Server::getInstance()->broadcastMessage("Le joueur " . $sender->getName() . " vient d'etre mute pendant $data[2] minute(s) pour $data[3]");
                        }else{
                            $player->sendMessage($this->getConfig()->get("mute.no.raison"));
                        }
                    }else{
                        $player->sendMessage($this->getConfig()->get("mute.deja"));
                    }
                }else{
                    $player->sendMessage($this->getConfig()->get("mute.temps"));
                }
            } else {
                $player->sendMessage($this->getConfig()->get("mute.player.no"));
            }
        });

        $form->setTitle($this->getConfig()->get("mute.title"));
        $form->addLabel($this->getConfig()->get("mute.content"));
        $form->addInput($this->getConfig()->get("mute.input"));
        $form->addInput($this->getConfig()->get("mute.inputt"));
        $form->addInput($this->getConfig()->get("mute.inputr"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function onChat(PlayerChatEvent $event) : void {
        $player = $event->getPlayer();
        if (!empty($this->muted[$player->getName()])) {
            if ($this->muted[$player->getName()] > time()) {
                $event->setCancelled();
                $player->sendMessage($this->getConfig()->get("mute.force"));
            }
        }
    }

    public function Player($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            $sender = Server::getInstance()->getPlayer($result);
            if ($sender instanceof Player){
                $pos = $sender->getPosition();
                $player->teleport($pos);
                $player->sendMessage($this->getConfig()->get("tp.message"));
            }else{
                $player->sendMessage($this->getConfig()->get("tp.no"));
            }
        });
        $form->setTitle($this->getConfig()->get("tp.title"));
        $form->setContent($this->getConfig()->get("tp.content"));
        foreach($this->getServer()->getOnlinePlayers() as $online){
            $form->addButton($online->getName(), -1, "", $online->getName());
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public function entreBan($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result){
                case 0:
                    $this->Ban($player);
                    break;
                case 1:
                    $this->unBan($player);
                    break;
            }
        });
        $form->setTitle($this->getConfig()->get("entreban.title"));
        $form->setContent($this->getConfig()->get("entreban.content"));
        $form->addButton($this->getConfig()->get("ban.boutton"));
        $form->addButton($this->getConfig()->get("unban.button"));
        $form->sendToPlayer($player);
        return $form;
    }

    public function onJoin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $config = new Config($this->getDataFolder() . "Ban.yml", Config::YAML);

        if ($config->exists($name)){
            $c = $config->get($name);
            if($c["temps"] > time()){
                $player->kick($this->getConfig()->get("ban.join"));
                $event->setCancelled(true);
            }
        }
    }

    public function unBan($player)
    {
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if ($data[1] != null){
                $config = new Config($this->getDataFolder() . "Ban.yml", Config::YAML);
                $sender = $data[1];
                if ($config->exists($sender)) {
                    $config->remove($sender);
                    $config->save();
                    $custommessage = $this->getConfig()->get("kick.message");
                    $message = str_replace("{player}", $sender->getName(), $custommessage);
                    $player->sendMessage($message);
                } else {
                    $player->sendMessage($this->getConfig()->get("unban.no.player"));
                }
            }
        });

        $form->setTitle($this->getConfig()->get("unban.title"));
        $form->addLabel($this->getConfig()->get("unban.content"));
        $form->addInput($this->getConfig()->get("unban.input"));
        $form->sendToPlayer($player);
        return $form;
    }
}