<?php

namespace Digueloulou12\ProtectArea\Forms;

use Digueloulou12\ProtectArea\ProtectArea;
use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EditForm implements Form
{
    private string $areaName;
    protected array $data = [];
    private ?\Closure $callable;

    private array $options = [
        "break",
        "place",
        "enderpearl",
        "egg",
        "snowball",
        "bow",
        "pvp",
        "use",
        "drop-items",
        "drop-death",
        "invincible",
        "fall-damage",
        "hunger",
        "explosion"
    ];

    private array $labelMap = [];

    public function __construct(string $areaName)
    {
        $this->areaName = $areaName;
        $this->data["type"] = "custom_form";
        $this->initText();
        $this->callable = function (Player $player, array $data = null) {
            if (is_null($data)) return;

            $api = ProtectArea::getInstance()->getProtectAreaAPI();
            if ($api->existArea($this->areaName)) {
                foreach ($this->options as $option) {
                    $api->updateOption($this->areaName, $option, $data[$option]);
                }
                $player->sendMessage(Utils::getConfigReplace("edit_area"));
            } else $player->sendMessage(Utils::getConfigReplace("no_exist_area"));
        };
    }

    public function processData(&$data): void
    {
        if (is_array($data)) {
            $new = [];
            foreach ($data as $i => $v) {
                $new[$this->labelMap[$i]] = $v;
            }
            $data = $new;
        }
    }

    public function initText(): void
    {
        $this->data["title"] = Utils::getConfigReplace("title");
        $this->data["content"] = [];

        foreach ($this->options as $option) {
            $this->addToggle($option, ProtectArea::getInstance()->getProtectAreaAPI()->data->get($this->areaName)[$option]);
        }
    }

    public function addToggle(string $text, bool $default): void
    {
        $content = ["type" => "toggle", "text" => $text];
        $content["default"] = $default;
        $this->data["content"][] = $content;
        $this->labelMap[] = $text;
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->callable;
        $callable($player, $data);
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}