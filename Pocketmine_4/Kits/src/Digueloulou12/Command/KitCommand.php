<?php

namespace Digueloulou12\Command;

use pocketmine\command\CommandSender;
use Digueloulou12\Forms\KitForms;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Kits;

class KitCommand extends Command
{
    public function __construct()
    {
        $command = Kits::getInstance()->getConfig();
        parent::__construct($command->get("command")[0]);
        if (isset($command->get("command")[1])) $this->setDescription($command->get("command")[1]);
        $this->setAliases($command->get("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ((isset($args[0])) and ($args[0] === "add")) {
                if ($sender->hasPermission(Kits::getInstance()->getConfig()->get("permission_add"))) {
                    KitForms::addKitForm($sender);
                } else $sender->sendMessage(Kits::getInstance()->getConfig()->get("no_perm"));
            } elseif ((isset($args[0])) and $args[0] === "remove") {
                if ($sender->hasPermission(Kits::getInstance()->getConfig()->get("permission_add"))) {
                    if (isset($args[1])) {
                        if (Kits::getInstance()->getConfig()->getNested("kits.$args[1]") !== null) {
                            Kits::getInstance()->getConfig()->removeNested("kits.$args[1]");
                            Kits::getInstance()->getConfig()->save();
                            $sender->sendMessage(str_replace("{kit}", $args[1], Kits::getInstance()->getConfig()->get("remove_kit")));
                        } else $sender->sendMessage(Kits::getInstance()->getConfig()->get("no_exist"));
                    } else $sender->sendMessage(Kits::getInstance()->getConfig()->get("no_kit"));
                } else $sender->sendMessage(Kits::getInstance()->getConfig()->get("no_perm"));
            } else {
                if (empty(Kits::getInstance()->getConfig()->get("category"))) {
                    KitForms::listKit($sender);
                } else KitForms::listCategory($sender);
            }
        } else $sender->sendMessage(Kits::getInstance()->getConfig()->get("no_player"));
    }
}