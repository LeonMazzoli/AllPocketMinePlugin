<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\event\Listener;

class CustomKb extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $config = $this->getConfig();
        if ($command->getName() === "customkb") {
            if (isset($args[0]) and is_numeric($args[0])) {
                if (isset($args[1]) and ($this->getServer()->getWorldManager()->getWorldByName($args[1]) !== null)) $world = $args[1]; else $world = null;

                if ($world === null) {
                    $config->set("kb", $args[0]);
                    $sender->sendMessage(str_replace("{kb}", $args[0], $config->get("msg")));
                } else {
                    $worlds = $config->get("worlds");
                    $worlds[$world] = $args[0];
                    $config->set("worlds", $worlds);
                    $sender->sendMessage(str_replace(["{world}", "{kb}"], [$world, $args[0]], $config->get("msg_")));
                }
                $config->save();
            } else $sender->sendMessage($config->get("no_kb"));
        }
        return true;
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $config = $this->getConfig();
        $world = $event->getEntity()->getWorld()->getFolderName();
        if (isset($config->get("worlds")[$world])) {
            $event->setKnockBack($config->get("worlds")[$world][0]);
            $event->setAttackCooldown($config->get("worlds")[$world][1] ?? $event->getAttackCooldown());
        } else {
            $event->setKnockBack($config->get("kb"));
            $event->setAttackCooldown($config->get("attack_cooldown"));
        }
    }
}