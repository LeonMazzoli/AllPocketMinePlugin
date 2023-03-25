<?php

namespace Digueloulou12\CustomBox\BoxEntity;

use Digueloulou12\CustomBox\Commands\BoxCommand;
use Digueloulou12\CustomBox\Forms\BoxForms;
use Digueloulou12\CustomBox\Utils\Utils;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class BoxEntity extends Human
{
    protected $alwaysShowNameTag = true;
    private CompoundTag $tag;

    public function __construct(Location $location, Skin $skin, CompoundTag $nbt)
    {
        parent::__construct($location, $skin, $nbt);
        $this->tag = $nbt;
    }

    public function attack(EntityDamageEvent $source): void
    {
        if ($this->noDamageTicks > 0) return;
        if (!($source instanceof EntityDamageByEntityEvent)) return;
        $player = $source->getDamager();
        if (!($player instanceof Player)) return;

        if ($this->remove($player)) return;

        $player->sendForm(new BoxForms($this->tag->getString("boxName")));
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool
    {
        if ($this->remove($player)) return true;

        $player->sendForm(new BoxForms($this->tag->getString("boxName")));
        return true;
    }

    private function remove(Player $player): bool
    {
        if (in_array($player->getName(), BoxCommand::$players)) {
            $this->flagForDespawn();
            unset(BoxCommand::$players[array_search($player->getName(), BoxCommand::$players)]);
            $player->sendMessage(Utils::getConfigReplace("remove_box"));
            return true;
        }
        return false;
    }

    public function saveNBT(): CompoundTag
    {
        return parent::saveNBT()->setString("boxName", $this->tag->getString("boxName"));
    }
}