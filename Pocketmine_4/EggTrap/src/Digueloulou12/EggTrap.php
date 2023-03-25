<?php

namespace Digueloulou12;

use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\entity\projectile\Egg;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockFactory;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\player\Player;
use pocketmine\block\Cobweb;
use pocketmine\Server;

class EggTrap extends PluginBase implements Listener
{
    public array $blocks = [];
    public array $time = [];

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "cobweb_time" => 10,
                "cooldown" => 15,
                "popup" => "Vous devez attentre encore {time} !"
            ]);
        }
    }

    public function onLaunch(ProjectileLaunchEvent $event)
    {
        $player = $event->getEntity()->getOwningEntity();

        if ($player instanceof Player) {
            $egg = $event->getEntity();
            if ($egg instanceof Egg) {
                if ((empty($this->time[$player->getName()])) or ($this->time[$player->getName()] < time())) {
                    $this->time[$player->getName()] = time() + $this->getConfig()->get("cooldown");
                } else {
                    $time = $this->time[$player->getName()] - time();
                    $player->sendPopup(str_replace("{time}", $time, $this->getConfig()->get("popup")));
                    $event->cancel();
                }
            }
        }
    }

    public function onHit(ProjectileHitEntityEvent $event)
    {
        $entity = $event->getEntityHit();

        if ($entity instanceof Player) {
            $egg = $event->getEntity();
            if ($egg instanceof Egg) {
                $x = $entity->getPosition()->getX();
                $y = $entity->getPosition()->getY();
                $z = $entity->getPosition()->getZ();
                $world = $entity->getWorld()->getId();

                $blocks = [
                    // [$x, $y, $z, $world], [$x, $y + 1, $z, $world],
                    [$x + 1, $y, $z, $world], [$x - 1, $y, $z, $world],
                    [$x + 1, $y + 1, $z, $world], [$x - 1, $y + 1, $z, $world],
                    [$x, $y, $z + 1, $world], [$x, $y, $z - 1, $world],
                    [$x, $y + 1, $z + 1, $world], [$x, $y + 1, $z - 1, $world],
                    [$x, $y + 2, $z, $world]


                    // [$x + 1, $y, $z + 1, $world], [$x + 1, $y + 1, $z + 1, $world],
                    // [$x - 1, $y, $z - 1, $world], [$x - 1, $y + 1, $z - 1, $world],
                    // [$x + 1, $y, $z - 1, $world], [$x + 1, $y + 1, $z - 1, $world],
                    // [$x - 1, $y, $z + 1, $world], [$x - 1, $y + 1, $z + 1, $world]
                ];
                $this->blocks[$entity->getName()] = $blocks;

                new class($entity->getName(), $this->getConfig()->get("cobweb_time"), $this) extends Task {
                    private EggTrap $eggTrap;
                    private string $sender;
                    private int $time;

                    public function __construct(string $name, int $time, EggTrap $eggTrap)
                    {
                        $this->time = $time;
                        $this->sender = $name;
                        $this->eggTrap = $eggTrap;
                        $eggTrap->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
                    }

                    public function onRun(): void
                    {
                        if ($this->time == 0) {
                            foreach ($this->eggTrap->blocks[$this->sender] as $block) {
                                $pos = new Position($block[0], $block[1], $block[2], Server::getInstance()->getWorldManager()->getWorld($block[3]));
                                if ($pos->getWorld()->getBlock($pos) instanceof Cobweb) {
                                    $pos->getWorld()->setBlock($pos, BlockFactory::getInstance()->get(BlockLegacyIds::AIR, 0), true);
                                }
                            }
                            $this->getHandler()->cancel();
                            return;
                        }
                        $this->time--;
                    }
                };

                foreach ($blocks as $block) {
                    $pos = new Position($block[0], $block[1], $block[2], Server::getInstance()->getWorldManager()->getWorld($block[3]));
                    if ($pos->getWorld()->getBlock($pos)->getId() == 0) {
                        $entity->getWorld()->setBlock($pos, BlockFactory::getInstance()->get(BlockLegacyIds::COBWEB, 0), true);
                    }
                }
            }
        }
    }
}