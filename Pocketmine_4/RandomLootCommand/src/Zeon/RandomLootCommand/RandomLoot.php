<?php

namespace Zeon\RandomLootCommand;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class RandomLoot
{
    public const COMMAND = "randomCommand";
    public const ITEM = "randomItem";

    private string $command;
    /** @var Item[] */
    private array $items;
    private string $name;
    private string $type;
    private int $chance;

    public function __construct(string $name, int $chance)
    {
        $this->name = $name;
        $this->chance = $chance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChance(): int
    {
        return $this->chance;
    }

    public function execute(Player $player): self
    {
        if ($this->type === self::COMMAND) {
            Server::getInstance()->getCommandMap()->dispatch(
                new ConsoleCommandSender(Server::getInstance(), $player->getLanguage()),
                str_replace('{name}', $player->getName(), $this->command)
            );
            return $this;
        }

        foreach ($this->items as $item) {
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else $player->dropItem($item);
        }
        return $this;
    }

    public function setCommand(string $command): self
    {
        $this->type = self::COMMAND;
        $this->command = $command;
        return $this;
    }

    public function setItem(Item ...$items): self
    {
        $this->type = self::ITEM;
        $this->items = $items;
        return $this;
    }
}