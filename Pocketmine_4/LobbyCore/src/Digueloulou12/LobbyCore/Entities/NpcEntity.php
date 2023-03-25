<?php

namespace Digueloulou12\LobbyCore\Entities;

use Digueloulou12\LobbyCore\API\ServerInfo;
use Digueloulou12\LobbyCore\Commands\NpcCommand;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class NpcEntity extends Human
{
    private string $customName = "";
    protected $alwaysShowNameTag = true;
    private float $customScale = 3.00;
    private string $ip;
    private int $port;

    public function __construct(Location $location, Skin $skin, CompoundTag $nbt)
    {
        parent::__construct($location, $skin, $nbt);
    }

    public function attack(EntityDamageEvent $source): void
    {
        if ($this->noDamageTicks > 0) return;
        if (!($source instanceof EntityDamageByEntityEvent)) return;
        $player = $source->getDamager();
        if (!($player instanceof Player)) return;

        if (in_array($player->getName(), NpcCommand::$id)) {
            $player->sendMessage(Utils::getConfigReplace("id_msg", "{id}", $this->getId()));
            unset(NpcCommand::$id[array_search($player->getName(), NpcCommand::$id)]);
            return;
        }

        $player->transfer($this->getIp(), $this->getPort());
    }

    public function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);

        $this->ip = $nbt->getString("ip");
        $this->port = $nbt->getInt("port");
        $this->customScale = $nbt->getFloat("customScale");
        $this->customName = $nbt->getString("customName");

        $this->setScale($nbt->getFloat("customScale"));

        $this->updateNameTag();
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool
    {
        if (in_array($player->getName(), NpcCommand::$id)) {
            $player->sendMessage(Utils::getConfigReplace("id_msg", "{id}", $this->getId()));
            unset(NpcCommand::$id[array_search($player->getName(), NpcCommand::$id)]);
            return true;
        }

        $player->transfer($this->getIp(), $this->getPort());
        return true;
    }

    public function updateNameTag(): void
    {
        $server = new ServerInfo($this->getIp(), $this->getPort());
        $online = $server->getOnlinePlayers();
        $maxOnline = $server->getMaxPlayers();
        $this->setNameTag(Utils::getConfigReplace("nametag_npc", ["{online}", "{max_online}", "{name}"], [$online, $maxOnline, $this->customName]));
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setScale(float $value): void
    {
        parent::setScale($value);
        $this->customScale = $value;
    }

    public function saveNBT(): CompoundTag
    {
        return parent::saveNBT()
            ->setString("ip", $this->getIp())
            ->setInt("port", $this->getPort())
            ->setFloat("customScale", $this->customScale)
            ->setString("customName", $this->customName);
    }
}