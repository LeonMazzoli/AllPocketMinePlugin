<?php

namespace Report;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Report\Commands\Report;
use Report\Commands\ReportAdmin;

class ReportMain extends PluginBase{
    private static $main;

    public function onEnable()
    {
        self::$main = $this;

        $this->saveResource("data.json");
        $this->saveResource("config.yml");

        // Config
        if ($this->getConfigValue("version") != 1) {
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->error("Your configuration file is outdated.");
            $this->getLogger()->error("Your old configuration has been saved as config_old.yml and a new configuration file has been generated.");
        }

        // Commands
        $info_report = explode(":", $this->getConfigValue("report"));
        $info_reportadmin = explode(":", $this->getConfigValue("reportadmin"));
        $this->getServer()->getCommandMap()->register($info_report[0], new Report($this));
        $this->getServer()->getCommandMap()->register($info_reportadmin[0], new ReportAdmin($this));
    }

    public static function getInstance(): ReportMain{
        return self::$main;
    }

    public function getConfigValue(string $value){
        $config = new Config(ReportMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        return $config->get($value);
    }
}