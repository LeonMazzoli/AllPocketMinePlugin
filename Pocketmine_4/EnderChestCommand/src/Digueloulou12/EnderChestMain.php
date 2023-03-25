<?php

namespace Digueloulou12;

use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\command\CommandSender;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\player\Player;
use muqsit\invmenu\InvMenu;
use pocketmine\utils\Config;

class EnderChestMain extends PluginBase
{
    public static Config $config;

    public function onEnable(): void
    {
        if (file_exists($this->getDataFolder() . "config.yml")) self::$config = $this->getConfig(); else {
            self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "# command: [COMMAND, DESCRIPTION, PERMISSION]",
                "command" => ["enderchest", "EnderChest Command", "enderchest.use"],
                "command_aliases" => ["ec"],
                "chest_name" => "EnderChest"
            ]);
        }


        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->getServer()->getCommandMap()->register("EnderChestCommand", new class extends Command {
            public function __construct()
            {
                $command = EnderChestMain::$config->get("command");
                parent::__construct($command[0], $command[1] ?? null, "", EnderChestMain::$config->get("command_aliases"));
            }

            public function execute(CommandSender $sender, string $commandLabel, array $args)
            {
                if ($sender instanceof Player) {
                    $command = EnderChestMain::$config->get("command");
                    if ((isset($command[2])) and !($sender->hasPermission($command[2]))) return;
                    $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
                    $menu->setName(EnderChestMain::$config->get("chest_name"));
                    $menu->getInventory()->setContents($sender->getEnderInventory()->getContents());
                    $item = $menu->getInventory()->getItem(0);
                    $item->getNamedTag()->setString("EnderChestCommand", "EnderChestCommand");
                    $menu->getInventory()->setItem(0, $item);
                    $menu->setListener(function (InvMenuTransaction $transaction) use ($sender): InvMenuTransactionResult {
                        $sender->getEnderInventory()->setItem($transaction->getAction()->getSlot(), $transaction->getIn());
                        return $transaction->continue();
                    });
                    $menu->send($sender);
                }
            }
        });
    }
}