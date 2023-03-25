<?php

namespace Digueloulou12\Welcome;

use Digueloulou12\Welcome\Command\WelcomeCommand;
use Digueloulou12\Welcome\Event\WelcomeEvent;
use Digueloulou12\Welcome\Utils\Utils;
use pocketmine\plugin\PluginBase;

class Welcome extends PluginBase
{
    private ?string $newPlayer = null;
    private array $players = [];
    private static self $this;
    private int $time = 0;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("WelcomeCommand", new WelcomeCommand(
            Utils::getConfigValue("command")[0] ?? "welcome",
            Utils::getConfigValue("command")[1] ?? "",
            Utils::getConfigValue("command_aliases") ?? []
        ));
        $this->getServer()->getPluginManager()->registerEvents(new WelcomeEvent(), $this);
    }

    public function getNewPlayer(): ?string
    {
        return $this->newPlayer;
    }

    public function isValidTime(): bool
    {
        return $this->time > time();
    }

    public function addPlayerWelcome(string $player): void
    {
        $this->players[] = $player;
    }

    public function alreadyWelcome(string $player): bool
    {
        return in_array($player, $this->players);
    }

    public function setNewPlayer(string $player): void
    {
        $this->players = [];
        $this->newPlayer = $player;
        $this->time = time() + Utils::getConfigValue("time");
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}