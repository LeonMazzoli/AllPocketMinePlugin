<?php

namespace Digueloulou12\UltraHardCore\Tasks;

use Digueloulou12\UltraHardCore\API\UltraHardCoreAPI;
use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\scheduler\Task;

class UltraHardCoreTask extends Task
{
    private int $waiting;
    private string $type;
    private int $mine;
    private int $pvp;

    public function __construct(string $type)
    {
        $this->type = $type;
        $this->waiting = Utils::getConfigValue("waiting_time");
    }

    public function onRun(): void
    {
        $api = UltraHardCore::getInstance()->getAPI();
        if ($this->type === UltraHardCoreAPI::WAITING) {
            if ($this->waiting === 0) {
                if (count($api->getAllPlayers()) >= Utils::getConfigValue("min_player")) {
                    $this->mine = Utils::getConfigValue("minage_time") * 60;
                    $api->sendMessage(Utils::getConfigReplace("game_start"));
                    $this->type = UltraHardCoreAPI::MINE;
                    $api->stopJoin();
                } else {
                    $this->waiting = Utils::getConfigValue("waiting_time");
                    $api->sendMessage(Utils::getConfigReplace("no_good_count"));
                }
            } else {
                if (in_array($this->waiting, Utils::getConfigValue("alert_waiting_time"))) {
                    $api->sendMessage(Utils::getConfigReplace("time_popup", "{time}", $this->waiting), true);
                }
            }
            $this->waiting--;
        } elseif ($this->type === UltraHardCoreAPI::MINE) {
            if ($this->mine === 0) {
                $this->pvp = Utils::getConfigValue("pvp_time") * 60;
                $api->setPvp(true);
            } else {
                if (in_array($this->mine / 60, Utils::getConfigValue("alert_mine_time"))) {
                    $time = $this->mine / 60;
                    $api->sendMessage(Utils::getConfigReplace("mine_popup", "{time}", $time), true);
                }
            }
            $this->mine--;
        } elseif ($this->type === UltraHardCoreAPI::PVP) {
            if ($this->pvp === 0) {
                $api->stopGame();
            } else {
                if (in_array($this->pvp / 60, Utils::getConfigValue("alert_pvp_time"))) {
                    $time = $this->pvp / 60;
                    $api->sendMessage(Utils::getConfigReplace("pvp_popup", "{time}", $time), true);
                }
            }
            $this->pvp--;
        }
    }
}