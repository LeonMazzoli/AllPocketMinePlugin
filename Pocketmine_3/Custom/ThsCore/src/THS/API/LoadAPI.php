<?php

namespace THS\API;

use pocketmine\Server;
use THS\Commands\Boutique\AddPb;
use THS\Commands\Boutique\Boutique;
use THS\Commands\Boutique\MyPb;
use THS\Commands\Boutique\RemovePb;
use THS\Commands\Boutique\SeePb;
use THS\Commands\Boutique\SetPb;
use THS\Commands\Build;
use THS\Commands\Gamemode;
use THS\Commands\God;
use THS\Commands\Grade;
use THS\Commands\Hub;
use THS\Commands\Language;
use THS\Commands\Lobby;
use THS\Commands\Money\AddMoney;
use THS\Commands\Money\MyMoney;
use THS\Commands\Money\Pay;
use THS\Commands\Money\RemoveMoney;
use THS\Commands\Money\SeeMoney;
use THS\Commands\Money\SetMoney;
use THS\Commands\Money\TopMoney;
use THS\Commands\Ping;
use THS\Commands\Reunion;
use THS\Commands\Sanction\Ban;
use THS\Commands\Sanction\EnderInvsee;
use THS\Commands\Sanction\Freeze;
use THS\Commands\Sanction\Invsee;
use THS\Commands\Sanction\Kick;
use THS\Commands\Sanction\Mute;
use THS\Commands\Sanction\Spy;
use THS\Commands\Sanction\Unmute;
use THS\Commands\Setkb;
use THS\Commands\Setpos;
use THS\Commands\Tps;
use THS\Events\BlockPlace;
use THS\Events\Chat;
use THS\Events\ConsumeEvent;
use THS\Events\DamageEvent;
use THS\Events\DeathEvent;
use THS\Events\DropEvent;
use THS\Events\GodEvent;
use THS\Events\InventoryTransaction;
use THS\Events\Join;
use THS\Events\Kb;
use THS\Events\KbGame;
use THS\Events\Kill;
use THS\Events\LoginEvent;
use THS\Events\MoveEvent;
use THS\Events\noHunger;
use THS\Events\PlayerCommand;
use THS\Events\Quit;
use THS\Items\Coal;
use THS\Items\Compass;
use THS\Items\EnderChest;
use THS\Items\EnderPearl;
use THS\Items\Feather;
use THS\Items\GappleStick;
use THS\Items\HealStick;
use THS\Items\HoeReunion;
use THS\Items\NetherStar;
use THS\Items\Popo;
use THS\Items\Settings;
use THS\Items\SpeedStick;

class LoadAPI
{
    private static $scoreboard;

    public static function getScoreboard(): ScoreboardAPI
    {
        return self::$scoreboard;
    }

    public static function unloadCommands()
    {
        $command = Server::getInstance()->getCommandMap();

        $command->unregister($command->getCommand("defaultgamemode"));
        $command->unregister($command->getCommand("extractplugin"));
        $command->unregister($command->getCommand("dumpmemory"));
        $command->unregister($command->getCommand("makeplugin"));
        $command->unregister($command->getCommand("genplugin"));
        $command->unregister($command->getCommand("gamerule"));
        $command->unregister($command->getCommand("gamemode"));
        // $command->unregister($command->getCommand("unban-ip"));
        $command->unregister($command->getCommand("save-all"));
        $command->unregister($command->getCommand("save-off"));
        $command->unregister($command->getCommand("save-on"));
        $command->unregister($command->getCommand("suicide"));
        // $command->unregister($command->getCommand("banlist"));
        // $command->unregister($command->getCommand("ban-ip"));
        // $command->unregister($command->getCommand("unban"));
        $command->unregister($command->getCommand("about"));
        // $command->unregister($command->getCommand("kick"));
        // $command->unregister($command->getCommand("ban"));
    }

    public static function loadCommands($main)
    {
        $command = Server::getInstance()->getCommandMap();

        $command->register("enderinvsee", new EnderInvsee($main));
        $command->register("language", new Language($main));
        $command->register("gamemode", new Gamemode($main));
        $command->register("boutique", new Boutique($main));
        $command->register("reunion", new Reunion($main));
        // $command->register("sanction", new Sanction($main));
        $command->register("unmute", new Unmute($main));
        $command->register("invsee", new Invsee($main));
        $command->register("freeze", new Freeze($main));
        $command->register("setpos", new Setpos($main));
        $command->register("setkb", new Setkb($main));
        $command->register("lobby", new Lobby($main));
        $command->register("build", new Build($main));
        $command->register("grade", new Grade($main));
        $command->register("mute", new Mute($main));
        $command->register("ping", new Ping($main));
        $command->register("kick", new Kick($main));
        $command->register("hub", new Hub($main));
        $command->register("tps", new Tps($main));
        $command->register("god", new God($main));
        $command->register("spy", new Spy($main));
        $command->register("ban", new Ban($main));

        // Money
        $command->register("removemoney", new RemoveMoney($main));
        $command->register("topmoney", new TopMoney($main));
        $command->register("addmoney", new AddMoney($main));
        $command->register("setmoney", new SetMoney($main));
        $command->register("seemoney", new SeeMoney($main));
        $command->register("mymoney", new MyMoney($main));
        $command->register("pay", new Pay($main));

        // Boutique
        $command->register("addpb", new AddPb($main));
        $command->register("removepb", new RemovePb($main));
        $command->register("setpb", new SetPb($main));
        $command->register("seepb", new SeePb($main));
        $command->register("mypb", new MyPb($main));
    }

    public static function loadEvents($main)
    {
        $server = Server::getInstance()->getPluginManager();

        // $server->registerEvents(new EntityDamageEventByEntitity(), $main);
        $server->registerEvents(new InventoryTransaction(), $main);
        $server->registerEvents(new PlayerCommand(), $main);
        $server->registerEvents(new ConsumeEvent(), $main);
        $server->registerEvents(new DamageEvent(), $main);
        $server->registerEvents(new LoginEvent(), $main);
        $server->registerEvents(new BlockPlace(), $main);
        $server->registerEvents(new DropEvent(), $main);
        // $server->registerEvents(new MoveEvent(), $main);
        $server->registerEvents(new GodEvent(), $main);
        $server->registerEvents(new noHunger(), $main);
        $server->registerEvents(new KbGame(), $main);
        $server->registerEvents(new Join(), $main);
        $server->registerEvents(new Quit(), $main);
        $server->registerEvents(new Kill(), $main);
        $server->registerEvents(new Chat(), $main);
        $server->registerEvents(new Kb(), $main);

        $server->registerEvents(new DeathEvent(), $main);

        // Items
        $server->registerEvents(new GappleStick(), $main);
        $server->registerEvents(new EnderChest(), $main);
        $server->registerEvents(new EnderPearl(), $main);
        $server->registerEvents(new SpeedStick(), $main);
        $server->registerEvents(new NetherStar(), $main);
        $server->registerEvents(new HoeReunion(), $main);
        $server->registerEvents(new HealStick(), $main);
        $server->registerEvents(new MoveEvent(), $main);
        $server->registerEvents(new Settings(), $main);
        $server->registerEvents(new Feather(), $main);
        $server->registerEvents(new Compass(), $main);
        $server->registerEvents(new Coal(), $main);
        $server->registerEvents(new Popo(), $main);
    }

    public static function setHandler()
    {
        self::$scoreboard = new ScoreboardAPI();
    }
}