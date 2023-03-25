<?php

namespace Digueloulou12\ProtectArea\API;

use Digueloulou12\ProtectArea\ProtectArea;
use JsonException;
use pocketmine\entity\Entity;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class ProtectAreaAPI
{
    private array $worlds;
    public Config $data;

    public function __construct()
    {
        $this->data = new Config(ProtectArea::getInstance()->getDataFolder() . "ProtectAreaData.json", Config::JSON);

        $worlds = [];
        foreach ($this->data->getAll() as $area => $value) {
            $worlds[$area] = explode("!", $value["pos-one"])[3];
        }
        $this->worlds = $worlds;
    }

    public function getAllArea(): array
    {
        return array_keys($this->worlds);
    }

    public function addArea(string $areaName, Position $one, Position $two): void
    {
        $this->worlds[$areaName] = $one->getWorld()->getFolderName();
        $this->data->set($areaName, [
            "break" => false,
            "place" => false,
            "enderpearl" => false,
            "egg" => false,
            "snowball" => false,
            "bow" => false,
            "pvp" => false,
            "use" => false,
            "drop-items" => false,
            "drop-death" => false,
            "invincible" => false,
            "fall-damage" => false,
            "hunger" => false,
            "explosion" => false,
            "pos-one" => $this->getStringByPosition($one),
            "pos-two" => $this->getStringByPosition($two)
        ]);
    }

    public function updateOption(string $areaName, string $option, bool $optionValue): void
    {
        $oldArray = $this->data->get($areaName);
        unset($oldArray[$option]);
        $oldArray[$option] = $optionValue;
        $this->data->set($areaName, $oldArray);
    }

    public function removeArea(string $areaName): void
    {
        unset($this->worlds[$areaName]);
        $this->data->remove($areaName);
    }

    public function existArea(string $areaName): bool
    {
        return $this->data->exists($areaName);
    }

    public function cancel(Entity $player, string $action, Position $position): bool
    {
        if (($player instanceof Player) and ($player->getServer()->isOp($player->getName()))) return false;
        if (in_array($position->getWorld()->getFolderName(), array_values($this->worlds))) {
            foreach ($this->worlds as $area => $world) {
                if ($world === $position->getWorld()->getFolderName()) {
                    $one = $this->getPositionByString($this->data->get($area)["pos-one"]);
                    $two = $this->getPositionByString($this->data->get($area)["pos-two"]);
                    $one = [$one->x, $one->y, $one->z];
                    $two = [$two->x, $two->y, $two->z];
                    if ($this->isInArea($one, $two, $position)) {
                        return !$this->data->get($area)[$action];
                        break;
                    }
                }
            }
        }
        return false;
    }

    private function isInArea(array $pos, array $pos_, Position $position): bool
    {
        if (($position->x >= min($pos[0], $pos_[0])) and ($position->x <= max($pos[0], $pos_[0])) and
            ($position->y >= min($pos[1], $pos_[1])) and ($position->y <= max($pos[1], $pos_[1])) and
            ($position->z >= min($pos[2], $pos_[2])) and ($position->z <= max($pos[2], $pos_[2]))) {
            return true;
        }
        return false;
    }

    public function getPositionByString(string $position): Position
    {
        $pos = explode("!", $position);
        return new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), Server::getInstance()->getWorldManager()->getWorldByName(strval($pos[3])));
    }

    public function getStringByPosition(Position $position): string
    {
        return "{$position->x}!{$position->y}!{$position->z}!{$position->getWorld()->getFolderName()}";
    }

    /**
     * @throws JsonException
     */
    public function save(): void
    {
        $this->data->save();
    }
}