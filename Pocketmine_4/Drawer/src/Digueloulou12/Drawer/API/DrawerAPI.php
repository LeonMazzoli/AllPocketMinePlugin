<?php

namespace Digueloulou12\Drawer\API;

use Digueloulou12\Drawer\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class DrawerAPI
{
    private array $drawers = [];
    private Config $data;

    public function init(Config $data): void
    {
        $this->data = $data;
        foreach ($data->getAll() as $pos => $value) {
            $this->drawers[$pos] = new Drawer($this->getPositionByString($pos), $value["item"], $value["max"]);
        }
    }

    public function existDrawer(Position $position): bool
    {
        return isset($this->drawers[$this->getStringByPosition($position)]);
    }

    public function getDrawer(Position $position): ?Drawer
    {
        return $this->drawers[$this->getStringByPosition($position)] ?? null;
    }

    public function createDrawer(Position $position, Block $block, string $item = null, int $max = null): void
    {
        $item = is_null($item) ? serialize(ItemFactory::air()->jsonSerialize()) : $item;
        $max = is_null($max) ? $this->getMaxItemByBlock($block) : $max;
        $this->data->set($this->getStringByPosition($position), ["item" => $item, "max" => $max]);
        $this->drawers[$this->getStringByPosition($position)] = new Drawer($position, $item, $max);
    }

    public function removeDrawer(Position $position, Block $block): Item
    {
        $item = $block->asItem();
        $drawer = $this->getDrawer($position);
        $item->getNamedTag()->setString("item", serialize($drawer->getItem()->jsonSerialize()));
        $item->getNamedTag()->setInt("max", $drawer->getMaxItem());
        $item->setCustomName(Utils::getConfigReplace("item_name", ["{name}", "{count}", "{max_count}"], [$drawer->getItem()->getName(), $drawer->getItem()->getCount(), $drawer->getMaxItem()]));
        unset($this->drawers[$this->getStringByPosition($position)]);
        $this->data->remove($this->getStringByPosition($position));
        return $item;
    }

    public function getPositionByString(string $string): Position
    {
        $pos = explode("!", $string);
        return new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
    }

    public function getStringByPosition(Position $position): string
    {
        return "{$position->x}!{$position->y}!{$position->z}!{$position->getWorld()->getFolderName()}";
    }

    public function isDrawer(Block $block): bool
    {
        foreach (Utils::getConfigValue("drawers") as $drawer) {
            if ($block->getId() === $drawer[0] and $block->getMeta() === $drawer[1]) {
                return true;
            }
        }
        return false;
    }

    public function update(Position $position): void
    {
        $drawer = $this->getDrawer($position);
        $this->data->set($this->getStringByPosition($position), ["item" => serialize($drawer->getItem()->jsonSerialize()), "max" => $drawer->getMaxItem()]);
    }

    public function getMaxItemByBlock(Block $block): int
    {
        foreach (Utils::getConfigValue("drawers") as $drawer) {
            if ($block->getId() === $drawer[0] and $block->getMeta() === $drawer[1]) {
                return $drawer[2];
            }
        }
        return 0;
    }

    public function save(): void
    {
        $this->data->save();
    }
}