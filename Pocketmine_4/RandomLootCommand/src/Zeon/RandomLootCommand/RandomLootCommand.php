<?php

namespace Zeon\RandomLootCommand;

use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class RandomLootCommand extends PluginBase
{
    /** @var RandomLoot[] */
    private array $loots;
    private Config $data;

    public function onEnable(): void
    {
        $this->data = new Config($this->getDataFolder() . 'RandomLootData.json', Config::JSON);
        $this->makeLoot();
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === 'randomloot') {
            if ($player instanceof Player){
                $restTime = $this->getTimePlayer($player);
                if (!(is_null($restTime)) and !(is_null($this->getUsePlayer($player)))) {
                    if (!$this->data->exists($player->getName())) {
                        $this->data->set($player->getName(), [
                            "time" => 0,
                            "use" => $this->getUsePlayer($player)
                        ]);
                        $player->sendMessage('Profil créé !');
                    }

                    $this->profilePlayer($time, $use, $player);
                    $this->timePlayer($time, $days, $hour, $minutes);
                    if (($time < time()) and ($use > 0)) {
                        $restUse = $use - 1;
                        $tt = $restUse > 0 ? 0 : time() + $restTime;
                        $this->data->set($player->getName(), [
                            'time' => $tt,
                            'use' => $restUse
                        ]);
                        $loot = $this->loots[array_rand($this->loots)]->execute($player);
                        $player->sendMessage("Vous avez reçu {$loot->getName()} !");
                    } else $player->sendMessage("Vous devez attendre encore $days jour(s), $hour hours et $minutes minute(s) !");
                } else $player->sendMessage("Vous n'avez pas la permission de jouer !");
            } else $player->sendMessage('La commande doit être executer en jeu !');
        }
        return false;
    }

    public function getTimePlayer(Player $player): ?int
    {
        $time = [ // The time is in day
            'vip.time' => 5,
            'player.time' => 7
        ];

        foreach ($time as $permission => $day) {
            if ($player->hasPermission($permission)) {
                return $day * 86400;
            }
        }
        return null;
    }

    public function getUsePlayer(Player $player): ?int
    {
        $use = [
            'vip.use' => 2,
            'player.use' => 1
        ];

        foreach ($use as $permission => $useInt) {
            if ($player->hasPermission($permission)) {
                return $useInt;
            }
        }
        return null;
    }

    public function timePlayer(int $time, &$days, &$hours, &$minutes): void
    {
        $remainingTime = $time - time();
        $days = floor($remainingTime / 86400);
        $hourSeconds = $remainingTime % 86400;
        $hours = floor($hourSeconds / 3600);
        $minuteSec = $hourSeconds % 3600;
        $minutes = floor($minuteSec / 60);
    }

    public function profilePlayer(&$time, &$use, Player $player): void
    {
        $time = $this->data->get($player->getName())['time'] ?? 0;
        $use = $this->data->get($player->getName())['use'] ?? 0;
    }

    public function makeLoot(): void
    {
        /** @var RandomLoot[] $loots */
        $loots = [
            (new RandomLoot('x1 Stone', 50))->setItem(VanillaBlocks::STONE()->asItem()),
            (new RandomLoot('x1 Stone Command', 50))->setCommand('give {name} stone 1')
        ];

        $total = 100;
        foreach ($loots as $loot) {
            $total -= $loot->getChance();
        }

        for ($i = 0;  $i < $total; $i++) {
            $this->loots[] = (new RandomLoot("x0 Air", 0))->setItem(VanillaItems::AIR());
        }

        foreach ($loots as $loot) {
            $total += $loot->getChance();
            for ($i = 0; $i < $loot->getChance(); $i++) {
                $this->loots[] = $loot;
            }
        }
    }

    public function onDisable(): void
    {
        $this->data->save();
    }
}