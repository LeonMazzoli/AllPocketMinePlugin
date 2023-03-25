<?php

namespace Digueloulou12\LobbyCore\Forms;

use Digueloulou12\LobbyCore\API\ServerInfo;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ServersForm implements Form
{
    private array $labelMap = [];
    protected array $data = [];
    private ?\Closure $callable;

    public function __construct()
    {
        $this->initText();
        $this->data["type"] = "form";
        $this->callable = function (Player $player, ?string $data = null) {
            if (is_null($data)) return;

            $player->transfer(Utils::getConfigValue("servers")[$data][0], Utils::getConfigValue("servers")[$data][1] ?? 13132);
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
        $this->data["title"] = Utils::getConfigValue("title") ?? "";
        $this->data["content"] = Utils::getConfigValue("content") ?? "";
        foreach (Utils::getConfigValue("servers") as $server => $value) {
            $online = (new ServerInfo($value[0], $value[1] ?? 19132))->getOnlinePlayers();
            $maxOnline = (new ServerInfo($value[0], $value[1] ?? 19132))->getMaxPlayers();
            $this->data["buttons"][] = ["text" => Utils::getConfigReplace("button", ["{name}", "{online}", "{max_online}"], [$server, $online, $maxOnline])];
            $this->labelMap[] = $server;
        }
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}