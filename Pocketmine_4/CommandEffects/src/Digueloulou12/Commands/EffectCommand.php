<?php

namespace Digueloulou12\Commands;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

class EffectCommand extends Command
{
    private ?string $permission;
    private array $effects;

    public function __construct(string $name, string $description, array $aliases, array $effects, ?string $permission = null)
    {
        parent::__construct($name, $description, null, $aliases);
        $this->permission = $permission;
        $this->effects = $effects;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (($this->permission !== null) and !($sender->hasPermission($this->permission))) return;
            foreach ($this->effects as $effect) {
                $sender->getEffects()->add(new EffectInstance(EffectIdMap::getInstance()->fromId($effect[0]), 20 * $effect[1], $effect[2], $effect[3]));
            }
        }
    }
}