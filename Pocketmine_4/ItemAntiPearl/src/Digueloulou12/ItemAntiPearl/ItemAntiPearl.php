<?php

namespace Digueloulou12\ItemAntiPearl;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ItemAntiPearl extends PluginBase implements Listener
{
    public array $players = [];

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "item" => [369, 0],
                "time" => 10,
                "player_popup" => "Vous avez été piégé pendant 10 secondes !",
                "sender_popup" => "Vous avez piégé votre adversaire pendant 10 secondes !"
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onLaunch(ProjectileLaunchEvent $event)
    {
        $player = $event->getEntity()->getOwningEntity();
        if (!($player instanceof Player)) return;
        if (isset($this->players[$player->getName()]) and $this->players[$player->getName()] > time()) {
            $event->cancel();
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player)) return;
        if (!($sender instanceof Player)) return;

        if ($event->isCancelled()) return;

        $item = $sender->getInventory()->getItemInHand();
        $ii = $this->getConfig()->get("item");
        if (($item->getId() === $ii[0]) and ($item->getMeta() === $ii[1])) {
            $this->players[$player->getName()] = time() + $this->getConfig()->get("time");
            $player->sendPopup($this->getConfig()->get("player_popup"));
            $sender->sendPopup($this->getConfig()->get("sender_popup"));
        }
    }
}