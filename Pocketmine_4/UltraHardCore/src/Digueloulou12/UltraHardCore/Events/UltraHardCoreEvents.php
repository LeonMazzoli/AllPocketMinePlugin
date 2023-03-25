<?php

namespace Digueloulou12\UltraHardCore\Events;

use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Pickaxe;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class UltraHardCoreEvents implements Listener
{
    public function onBreak(BlockBreakEvent $event)
    {
        $main = UltraHardCore::getInstance();
        if ($main->getAPI()->isGame()) {
            if (Utils::getConfigValue("break_furnace")) {
                $drops = match ($event->getBlock()->getId()) {
                    BlockLegacyIds::IRON_ORE => [VanillaItems::IRON_INGOT()],
                    BlockLegacyIds::GOLD_ORE => [VanillaItems::GOLD_INGOT()],
                    default => $event->getDrops()
                };
                $event->setDrops($drops);
            }
        }
    }

    public function onInventory(InventoryTransactionEvent $event)
    {
        $main = UltraHardCore::getInstance();
        if ($main->getAPI()->isGame()) {
            $player = $event->getTransaction()->getSource();
            foreach ($event->getTransaction()->getActions() as $action) {
                if ($action->getTargetItem() instanceof Pickaxe) {
                    $player->getInventory()->removeItem($action->getTargetItem());
                    $player->getInventory()->addItem($action->getTargetItem()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 2))->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2)));
                }
            }
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $sender = $event->getEntity();
        $player = $event->getDamager();

        if (!($sender instanceof Player)) return;
        if (!($player instanceof Player)) return;

        $main = UltraHardCore::getInstance();

        if (!($main->getAPI()->isInGame($sender))) return;
        if (!($main->getAPI()->isInGame($player))) return;

        if ($main->getAPI()->isGame()) {
            if (!$main->getAPI()->canPvP()) $event->cancel();
        }
    }

    public function onDamageMain(EntityDamageEvent $event)
    {
        $cause = $event->getCause();
        if ($cause === EntityDamageEvent::CAUSE_FALL) {
            if ((UltraHardCore::getInstance()->getAPI()->isGame()) and ($event->getEntity()->getWorld()->getFolderName() === Utils::getConfigValue("game_world"))) {
                $event->cancel();
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $main = UltraHardCore::getInstance();
        if ($main->getAPI()->isGame()) {
            if ($main->getAPI()->isInGame($event->getPlayer())) {
                $main->getAPI()->removePlayer($event->getPlayer(), true);
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $main = UltraHardCore::getInstance();
        if ($main->getAPI()->isGame()) {
            if ($main->getAPI()->isInGame($event->getPlayer())) {
                $main->getAPI()->removePlayer($event->getPlayer());
            }
        }
    }
}