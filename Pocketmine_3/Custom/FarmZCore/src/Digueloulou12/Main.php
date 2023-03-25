<?php

namespace Digueloulou12;

use Digueloulou12\API\ConfigAPI;
use Digueloulou12\API\DiscordAPI;
use Digueloulou12\API\RankAPI;
use Digueloulou12\API\SkyblockAPI;
use Digueloulou12\Commands\Feed;
use Digueloulou12\Commands\Furnace;
use Digueloulou12\Commands\Heal;
use Digueloulou12\Commands\Is;
use Digueloulou12\Commands\Message;
use Digueloulou12\Commands\Money\AddMoney;
use Digueloulou12\Commands\Money\MyMoney;
use Digueloulou12\Commands\Money\Pay;
use Digueloulou12\Commands\Money\RemoveMoney;
use Digueloulou12\Commands\Money\Seemoney;
use Digueloulou12\Commands\Money\SetMoney;
use Digueloulou12\Commands\Money\TopMoney;
use Digueloulou12\Commands\Report;
use Digueloulou12\Commands\ReportAdmin;
use Digueloulou12\Commands\Staff;
use Digueloulou12\Commands\Top;
use Digueloulou12\Commands\Warp;
use Digueloulou12\Commands\XpBottle;
use Digueloulou12\Events\ArmorEvent;
use Digueloulou12\Events\BlockEvents;
use Digueloulou12\Events\ChatEvent;
use Digueloulou12\Events\DeathEvent;
use Digueloulou12\Events\EntityDamageEvent;
use Digueloulou12\Events\Items;
use Digueloulou12\Events\JoinEvent;
use Digueloulou12\Events\QuitEvent;
use Digueloulou12\Tasks\ClearLaggTask;
use Digueloulou12\Tasks\CombatTask;
use Digueloulou12\Tasks\MotdTask;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public static string $prefix;
    public static Config $players;
    public static Config $config;
    private static Main $main;
    private static ConfigAPI $config_api;
    private static DiscordAPI $discord_api;

    public function onEnable()
    {
        $this->saveResource("reports.json");
        $this->saveResource("warps.json");
        $this->saveResource("config.yml");
        self::$players = new Config($this->getDataFolder() . "players.json", Config::JSON);
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        self::$prefix = self::$config->get("prefix");
        self::$main = $this;
        self::$config_api = new ConfigAPI();
        self::$discord_api = new DiscordAPI();

        self::$discord_api->sendMessage(self::getConfigAPI()->getConfigValue("start"));

        new SkyblockAPI();
        new RankAPI();

        foreach (self::$config->get("unload_commands") as $unload_command) {
            $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand($unload_command));
        }

        $command = Server::getInstance()->getCommandMap();

        // Money
        if (self::$config->get("money") === true) {
            $command->register("", new RemoveMoney());
            $command->register("", new TopMoney());
            $command->register("", new SetMoney());
            $command->register("", new AddMoney());
            $command->register("", new Seemoney());
            $command->register("", new MyMoney());
            $command->register("", new Pay());
        }

        // Commands
        if (self::$config->get("reportadminn") === true) $command->register("", new ReportAdmin());
        if (self::$config->get("xppbottle") === true) $command->register("", new XpBottle());
        if (self::$config->get("furnacee") === true) $command->register("", new Furnace());
        if (self::$config->get("reportt") === true) $command->register("", new Report());
        if (self::$config->get("stafff") === true) $command->register("", new Staff());
        if (self::$config->get("msgg") === true) $command->register("", new Message());
        if (self::$config->get("warpp") === true) $command->register("", new Warp());
        if (self::$config->get("feeed") === true) $command->register("", new Feed());
        if (self::$config->get("heaal") === true) $command->register("", new Heal());
        if (self::$config->get("topp") === true) $command->register("", new Top());
        if (self::$config->get("iis") === true) $command->register("", new Is());

        // Events
        $server = Server::getInstance()->getPluginManager();
        $server->registerEvents(new EntityDamageEvent(), $this);
        $server->registerEvents(new BlockEvents(), $this);
        $server->registerEvents(new DeathEvent(), $this);
        $server->registerEvents(new ArmorEvent(), $this);
        $server->registerEvents(new QuitEvent(), $this);
        $server->registerEvents(new JoinEvent(), $this);
        $server->registerEvents(new ChatEvent(), $this);
        $server->registerEvents(new Items(), $this);

        // Tasks
        if (self::$config->get("clearlagg") === true) $this->getScheduler()->scheduleRepeatingTask(new ClearLaggTask(ConfigAPI::getConfigInt("time")), 20);
        if (self::$config->get("motdd") === true) $this->getScheduler()->scheduleRepeatingTask(new MotdTask(), 20 * self::$config->get("motd_time"));
        if (self::$config->get("combat_logger") === true) $this->getScheduler()->scheduleRepeatingTask(new CombatTask(), 20);
    }

    public static function getInstance(): Main
    {
        return self::$main;
    }

    public static function getDiscordAPI(): DiscordAPI
    {
        return self::$discord_api;
    }

    public static function getConfigAPI(): ConfigAPI
    {
        return self::$config_api;
    }

    public function onDisable()
    {
        self::$discord_api->sendMessage(self::getConfigAPI()->getConfigValue("stop"));
    }
}