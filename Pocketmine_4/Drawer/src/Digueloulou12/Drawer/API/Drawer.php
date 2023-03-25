<?php

namespace Digueloulou12\Drawer\API;

use pocketmine\item\Item;
use pocketmine\world\Position;

class Drawer
{
    private Position $position;
    private string $item;
    private int $max;

    public function __construct(Position $position, string $item, int $max)
    {
        $this->position = $position;
        $this->item = $item;
        $this->max = $max;
    }

    public function getItem(): ?Item
    {
        return Item::jsonDeserialize(unserialize($this->item));
    }

    public function setItem(Item $item): void
    {
        $this->item = serialize($item->jsonSerialize());
        $this->update();
    }

    public function addItem(Item $item): void
    {
        $this->setItem($item->setCount($this->getItem()->getCount() + $item->getCount()));
        $this->update();
    }

    public function removeItem(Item $item): void
    {
        $this->setItem($item->setCount($this->getItem()->getCount() - $item->getCount()));
        $this->update();
    }

    public function setMaxItem(int $max): void
    {
        $this->max = $max;
        $this->update();
    }

    public function getMaxItem(): int
    {
        return $this->max;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function canAddItem(Item $item): bool
    {
        return ($item->getCount() + $this->getItem()->getCount()) <= $this->getMaxItem();
    }

    public function isEmpty(): bool
    {
        return $this->getItem()->getCount() === 0;
    }

    public function update(): void
    {
        \Digueloulou12\Drawer\Drawer::getInstance()->getAPI()->update($this->getPosition());
    }
}