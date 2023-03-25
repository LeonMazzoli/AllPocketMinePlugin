<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\World;
use pocketmine\Server;

class PurificationArea extends PluginBase
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        foreach ($this->getConfig()->get("area") as $name => $area) {
            if ($this->getServer()->getWorldManager()->getWorldByName($area[2]) !== null) {
                $this->getScheduler()->scheduleRepeatingTask(new class($area, $name, $this->getConfig()) extends Task {
                    private array $player = [];
                    private Config $config;
                    private array $output;
                    private string $name;
                    private array $input;
                    private World $world;
                    private array $pos_;
                    private array $pos;
                    private int $time;

                    public function __construct(array $area, string $name, Config $config)
                    {
                        $this->world = Server::getInstance()->getWorldManager()->getWorldByName($area[2]);
                        $this->pos = $area[0];
                        $this->pos_ = $area[1];

                        $this->time = $area[3];

                        $this->name = $name;

                        $this->input = $area[4];
                        $this->output = $area[5];

                        $this->config = $config;
                    }

                    public function onRun(): void
                    {
                        foreach ($this->world->getPlayers() as $player) {
                            if ($this->isInArea($this->pos, $this->pos_, $player)) {
                                if (!isset($this->player[$player->getName()])) {
                                    $this->player[$player->getName()] = 0;
                                    $player->sendMessage(str_replace("{name}", $this->name, $this->config->get("messages")["walk_in"] ?? ""));
                                }
                                if (($player->getInventory()->getItemInHand()->getId() === $this->input[0] ?? 0) and
                                    ($player->getInventory()->getItemInHand()->getMeta() === $this->input[1] ?? 1) and
                                    ($player->getInventory()->getItemInHand()->getCount() >= $this->input[2] ?? 1)) {
                                    if (!isset($this->player[$player->getName()])) {
                                        $this->player[$player->getName()] = 0;
                                    }

                                    if ($this->player[$player->getName()] !== $this->time) {
                                        $this->player[$player->getName()]++;
                                        $percentage = $this->player[$player->getName()] / $this->time;
                                        $percentage = $percentage * 100;
                                        $player->sendPopup(str_replace("{%}", $percentage, $this->config->get("messages")["purification"] ?? "{%}%%"));
                                    } else {
                                        $this->player[$player->getName()] = 0;
                                        $item = ItemFactory::getInstance()->get($this->output[0] ?? 0, $this->output[1] ?? 0, $this->output[2] ?? 1);
                                        if ($player->getInventory()->canAddItem($item)) {
                                            $player->getInventory()->addItem($item);
                                        } else $player->getWorld()->dropItem($player->getPosition(), $item);
                                        $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - $this->input[2] ?? 1));
                                    }
                                } else {
                                    if (isset($this->player[$player->getName()])) $this->player[$player->getName()] = 0;
                                    $player->sendTip($this->config->get("messages")["no_item"] ?? "");
                                }
                            } else {
                                if (isset($this->player[$player->getName()])) {
                                    $player->sendMessage(str_replace("{name}", $this->name, $this->config->get("messages")["go_out"] ?? ""));
                                    unset($this->player[$player->getName()]);
                                }
                            }
                        }
                    }

                    private function isInArea(array $pos, array $pos_, Player $player): bool
                    {
                        if (($player->getPosition()->x >= min($pos[0], $pos_[0])) and ($player->getPosition()->x <= max($pos[0], $pos_[0])) and
                            ($player->getPosition()->y >= min($pos[1], $pos_[1])) and ($player->getPosition()->y <= max($pos[1], $pos_[1])) and
                            ($player->getPosition()->z >= min($pos[2], $pos_[2])) and ($player->getPosition()->z <= max($pos[2], $pos_[2]))) {
                            return true;
                        }
                        return false;
                    }
                }, 20);
            } else $this->getLogger()->alert("THE WORLD $area[2] DO NOT EXIST !");
        }
    }
}