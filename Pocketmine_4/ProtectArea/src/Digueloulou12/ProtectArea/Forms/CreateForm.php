<?php

namespace Digueloulou12\ProtectArea\Forms;

use Digueloulou12\ProtectArea\Events\PlayerEvents;
use Digueloulou12\ProtectArea\ProtectArea;
use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class CreateForm implements Form
{
    protected array $data = [];
    /** @var callable|null */
    private $callable;
    private array $labelMap = [];

    public function __construct()
    {
        $this->callable = function (Player $player, array $data = null) {
            if (is_null($data)) return;

            $area = $data[0];
            if ($area !== "") {
                $api = ProtectArea::getInstance()->getProtectAreaAPI();
                if (!$api->existArea($area)) {
                    PlayerEvents::$players[$player->getName()] = ["name" => $area];
                    $player->sendMessage(Utils::getConfigReplace("click"));
                } else $player->sendMessage(Utils::getConfigReplace("already_exist"));
            } else $player->sendMessage(Utils::getConfigReplace("no_area"));
        };
        $this->data["type"] = "custom_form";
        $this->initText();
    }

    public function initText(): void
    {
        $this->data["title"] = Utils::getConfigReplace("title");
        $this->data["content"] = [];

        $this->data["content"][] = ["type" => "input", "text" => "Area", "placeholder" => "DigueArea", "default" => null];
        $this->labelMap[] = $label ?? count($this->labelMap);
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