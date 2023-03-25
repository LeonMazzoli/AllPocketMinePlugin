<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Token;

class TokenMenu extends Command
{
    public function __construct()
    {
        parent::__construct(Token::getMain()->getConfig()->get("tokenmenu")[0]);
        if (isset(Token::getMain()->getConfig()->get("tokenmenu")[1])) $this->setDescription(Token::getMain()->getConfig()->get("tokenmenu")[1]);
        $this->setAliases(Token::getMain()->getConfig()->get("tokenmenu_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset(Token::getMain()->getConfig()->get("tokenmenu")[2])) {
            if (!$sender->hasPermission(Token::getMain()->getConfig()->get("tokenmenu")[2])) {
                $sender->sendMessage(Token::getMain()->getConfig()->get("no_perm"));
                return;
            }
        }

        $config = Token::getMain()->getConfig();
        if ($sender instanceof Player) {
            $form = new SimpleForm(function (Player $player, int $data = null){
                return;
            });
            $form->setTitle($config->get("title"));
            $form->setContent(str_replace("{token}", Token::getMain()->getToken($sender), $config->get("content")));
            $form->addButton($config->get("button"));
            $form->sendToPlayer($sender);
        }
    }
}