<?php

namespace Digueloulou12\Command;

use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Digueloulou12\Atm;

class AtmCommand extends Command
{
    public function __construct()
    {
        $config = Atm::getAtm()->getConfig();
        parent::__construct($config->get("command")[0]);
        if (isset($config->get("command")[1])) $this->setDescription($config->get("command")[1]);
        $this->setAliases($config->get("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (Atm::getAtm()->getAtmData()->exists($sender->getName())) {
                $form = new SimpleForm(function (Player $player, int $data = null) {
                    if ($data === null) return;

                    $money = Atm::getAtm()->getAtmData()->get($player->getName());
                    Atm::getAtm()->addMoney($player, $money);
                    $player->sendMessage(str_replace("{money}", $money, Atm::getAtm()->getConfig()->get("atm_msg")));
                    Atm::getAtm()->getAtmData()->set($player->getName(), 0);
                });
                $form->setTitle(Atm::getAtm()->getConfig()->get("title"));
                $form->setContent(str_replace("{money}", Atm::getAtm()->getAtmData()->get($sender->getName()), Atm::getAtm()->getConfig()->get("content")));
                $form->addButton(Atm::getAtm()->getConfig()->get("button"));
                $sender->sendForm($form);
            } else $sender->sendMessage(Atm::getAtm()->getConfig()->get("no_exist"));
        }
    }
}