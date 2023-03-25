<?php

namespace Digueloulou12\ProtectArea\Forms;

use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ProtectAreaForm implements Form
{
    private array $labelMap = [];
    protected array $data = [];
    private ?\Closure $callable;

    public function __construct()
    {
        $this->initText();
        $this->data["type"] = "form";
        $this->callable = function (Player $player, int $data = null) {
            if (is_null($data)) return;

            $form = match ($data) {
                0 => new CreateForm(),
                1 => new ListForm(),
                2 => new RemoveForm()
            };
            $player->sendForm($form);
        };
    }

    public function processData(&$data): void
    {
        $data = $this->labelMap[$data] ?? null;
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->callable;
        $callable($player, $data);
    }

    public function initText(): void
    {
        $this->data["title"] = Utils::getConfigReplace("title") ?? "";
        $this->data["content"] = Utils::getConfigReplace("content");

        $this->data["buttons"][] = ["text" => Utils::getConfigReplace("button_create")];
        $this->labelMap[] = count($this->labelMap);

        $this->data["buttons"][] = ["text" => Utils::getConfigReplace("button_edit")];
        $this->labelMap[] = count($this->labelMap);

        $this->data["buttons"][] = ["text" => Utils::getConfigReplace("button_remove")];
        $this->labelMap[] = count($this->labelMap);
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}