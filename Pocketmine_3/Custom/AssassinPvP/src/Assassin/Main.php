<?php

namespace Assassin;

use Assassin\Commands\Coords;
use Assassin\Commands\Edit;
use Assassin\Commands\Freeze;
use Assassin\Commands\Giveallkey;
use Assassin\Commands\Goarc;
use Assassin\Commands\Gobleu;
use Assassin\Commands\God;
use Assassin\Commands\Gogapple;
use Assassin\Commands\Gojaune;
use Assassin\Commands\Gokb;
use Assassin\Commands\Gopopo;
use Assassin\Commands\Gorouge;
use Assassin\Commands\Gosnow;
use Assassin\Commands\Gosumo;
use Assassin\Commands\Govert;
use Assassin\Commands\Goville;
use Assassin\Commands\Hub;
use Assassin\Commands\Laserui;
use Assassin\Commands\Lobby;
use Assassin\Commands\Mute;
use Assassin\Commands\Nick;
use Assassin\Commands\Redem;
use Assassin\Commands\setkb;
use Assassin\Commands\Setville;
use Assassin\Commands\Spy;
use Assassin\Commands\Sumo;
use Assassin\Commands\ToggleSprint;
use Assassin\Commands\Tps;
use Assassin\Commands\Unmute;
use Assassin\Events\AntiNoStuff;
use Assassin\Events\BreakEdit;
use Assassin\Events\ChatBvn;
use Assassin\Events\ChatMute;
use Assassin\Events\Damage;
use Assassin\Events\EnderPearl;
use Assassin\Events\Excalibur;
use Assassin\Events\GodEvent;
use Assassin\Events\ItemId;
use Assassin\Events\Join;
use Assassin\Events\Kb;
use Assassin\Events\KillEvent;
use Assassin\Events\Kits;
use Assassin\Events\Leave;
use Assassin\Events\Param;
use Assassin\Events\Popo;
use Assassin\Events\Portail;
use Assassin\Events\Snow;
use Assassin\Events\SpeedLobby;
use Assassin\Events\SpyEvent;
use Assassin\Events\SumoVoids;
use Assassin\Tasks\RedemTask;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Main extends PluginBase{
    public static $prefix = "§0[§bAssassin§0]§f ";
    private static $main;
    public function onEnable()
    {
        // Load
        $this->getLogger()->info("-------------------------------");
        $this->getLogger()->info("AssassinPvP on by Digueloulou12");
        $this->getLogger()->info("-------------------------------");

        $this->saveDefaultConfig();

        self::$main = $this;

        // Unload Commands
        $command = $this->getServer()->getCommandMap();
        $command->unregister($this->getServer()->getCommandMap()->getCommand("kick"));
        $command->unregister($this->getServer()->getCommandMap()->getCommand("me"));
        $command->unregister($this->getServer()->getCommandMap()->getCommand("suicide"));
        $command->unregister($this->getServer()->getCommandMap()->getCommand("gamerule"));
        $command->unregister($this->getServer()->getCommandMap()->getCommand("checkperm"));
        $command->unregister($this->getServer()->getCommandMap()->getCommand("transferserver"));

        // Commands
        $this->getServer()->getCommandMap()->register("togglesprint", new ToggleSprint($this));
        $this->getServer()->getCommandMap()->register("spy", new Spy($this));
        $this->getServer()->getCommandMap()->register("lobby", new Lobby($this));
        $this->getServer()->getCommandMap()->register("goarc", new Goarc($this));
        $this->getServer()->getCommandMap()->register("hub", new Hub($this));
        $this->getServer()->getCommandMap()->register("gopopo", new Gopopo($this));
        $this->getServer()->getCommandMap()->register("gogapple", new Gogapple($this));
        $this->getServer()->getCommandMap()->register("gosnow", new Gosnow($this));
        $this->getServer()->getCommandMap()->register("gokb", new Gokb($this));
        $this->getServer()->getCommandMap()->register("god", new God($this));
        $this->getServer()->getCommandMap()->register("setkb", new setkb($this));
        // $this->getServer()->getCommandMap()->register("gojaune", new Gojaune($this));
        // $this->getServer()->getCommandMap()->register("gobleu", new Gobleu($this));
        // $this->getServer()->getCommandMap()->register("gorouge", new Gorouge($this));
        // $this->getServer()->getCommandMap()->register("govert", new Govert($this));
        // $this->getServer()->getCommandMap()->register("redem", new Redem($this));
        $this->getServer()->getCommandMap()->register("tps", new Tps($this));
        // $this->getServer()->getCommandMap()->register("sumo", new Sumo($this));
        $this->getServer()->getCommandMap()->register("laserui", new Laserui($this));
        $this->getServer()->getCommandMap()->register("freeze", new Freeze($this));
        $this->getServer()->getCommandMap()->register("mute", new Mute($this));
        $this->getServer()->getCommandMap()->register("unmute", new Unmute($this));
        $this->getServer()->getCommandMap()->register("edit", new Edit($this));
        $this->getServer()->getCommandMap()->register("nick", new Nick($this));
        $this->getServer()->getCommandMap()->register("coords", new Coords($this));
        $this->getServer()->getCommandMap()->register("setville", new Setville($this));
        $this->getServer()->getCommandMap()->register("goville", new Goville($this));
        $this->getServer()->getCommandMap()->register("gosumo", new Gosumo($this));
        $this->getServer()->getCommandMap()->register("giveallkey", new Giveallkey($this));

        // Events
        $this->getServer()->getPluginManager()->registerEvents(new SpyEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Join(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new KillEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new GodEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Excalibur(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Snow(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Kits(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Kb(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Param(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Leave(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Popo(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ChatBvn(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Damage(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Portail(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EnderPearl(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ChatMute(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ItemId(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BreakEdit(), $this);
        // $this->getServer()->getPluginManager()->registerEvents(new AntiNoStuff(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SpeedLobby(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SumoVoids(), $this);

        // Tasks
        // $this->getScheduler()->scheduleRepeatingTask(new RedemTask($this), 20 * 5);
    }

    public function onDisable()
    {
        // Unload
        $this->getLogger()->info("--------------------------------");
        $this->getLogger()->info("AssassinPvP off by Digueloulou12");
        $this->getLogger()->info("--------------------------------");

        // Transfer
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        foreach (Server::getInstance()->getOnlinePlayers() as $players){
            $players->transfer($config->get("ip"), $config->get("port"));
        }
    }

    public static function getInstance(): Main{
        return self::$main;
    }
}