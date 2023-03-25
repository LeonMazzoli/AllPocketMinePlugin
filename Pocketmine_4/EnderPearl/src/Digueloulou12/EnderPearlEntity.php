<?php

namespace Digueloulou12;

use pocketmine\block\Tripwire;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\particle\EndermanTeleportParticle;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\projectile\Throwable;
use pocketmine\block\PressurePlate;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

class EnderPearlEntity extends Throwable
{
    public static function getNetworkTypeId(): string
    {
        return EntityIds::ENDER_PEARL;
    }

    protected function calculateInterceptWithBlock(Block $block, Vector3 $start, Vector3 $end): ?RayTraceResult
    {
        if ($block instanceof PressurePlate) $this->teleportOwner($block->getPosition());
        if ($block instanceof Tripwire) $this->teleportOwner($block->getPosition());
        return parent::calculateInterceptWithBlock($block, $start, $end);
    }

    protected function teleportOwner(Vector3 $vector): void
    {
        $owner = $this->getOwningEntity();
        if ($owner !== null) {
            $this->getWorld()->addParticle($origin = $owner->getPosition(), new EndermanTeleportParticle());
            $this->getWorld()->addSound($origin, new EndermanTeleportSound());
            $owner->teleport($target = $vector);
            $this->getWorld()->addSound($target, new EndermanTeleportSound());
            $owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
            $this->flagForDespawn();
        }
    }

    public function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void
    {
        $player = $this->getOwningEntity();
        if($player instanceof Player) {
            $blockResultVector = $hitResult->getHitVector();
            parent::onHitBlock($blockHit, $hitResult);
            if($blockHit->getId() === EnderPearlMain::getInstance()->getConfig()->get("block_id")) {
                $player->sendMessage(EnderPearlMain::getInstance()->getConfig()->get("cancel_msg"));
                $player->getInventory()->addItem(VanillaItems::ENDER_PEARL());
                return;
            }

            $this->teleportOwner($blockResultVector);
        }
    }
}