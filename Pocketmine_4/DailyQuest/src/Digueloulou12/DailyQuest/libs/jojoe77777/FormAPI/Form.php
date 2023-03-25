<?php

declare(strict_types = 1);

namespace Digueloulou12\DailyQuest\libs\jojoe77777\FormAPI;

use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm
{
    protected array $data = [];
    private $callable;

    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    public function sendToPlayer(Player $player): void
    {
        $player->sendForm($this);
    }

    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data): void
    {
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}