<?php

namespace Digueloulou12\ProtectArea\Forms;

use Digueloulou12\ProtectArea\ProtectArea;
use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class RemoveForm implements Form
{
    protected array $data = [];
    /** @var callable|null */
    private $callable;
    private array $labelMap = [];

    public function __construct()
    {
        $this->callable = function (Player $player, array $data = null) {
            if (is_null($data)) return;

            $api = ProtectArea::getInstance()->getProtectAreaAPI();
            if (count($api->getAllArea()) > 0) {
                if ($api->existArea($api->getAllArea()[$data[0]])) {
                    $area = $api->getAllArea()[$data[0]];
                    $api->removeArea($area);
                    $player->sendMessage(Utils::getConfigReplace("remove_area", "{area}", $area));
                } else $player->sendMessage(Utils::getConfigReplace("no_exist_area"));
            } else $player->sendMessage(Utils::getConfigReplace("no_exist_area"));
        };
        $this->data["type"] = "custom_form";
        $this->initText();
    }

    public function initText(): void
    {
        $this->data["title"] = Utils::getConfigReplace("title");
        $this->data["content"] = [];

        $api = ProtectArea::getInstance()->getProtectAreaAPI();
        $this->data["content"][] = ["type" => "dropdown", "text" => "Area", "options" => $api->getAllArea(), "default" => 0];
        $this->labelMap[] = count($this->labelMap);
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