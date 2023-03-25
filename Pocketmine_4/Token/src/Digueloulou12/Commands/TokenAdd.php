<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Token;
use pocketmine\Server;

class TokenAdd extends Command
{
    public function __construct()
    {
        parent::__construct(Token::getMain()->getConfig()->get("tokenadd")[0]);
        if (isset(Token::getMain()->getConfig()->get("tokenadd")[1])) $this->setDescription(Token::getMain()->getConfig()->get("tokenadd")[1]);
        $this->setAliases(Token::getMain()->getConfig()->get("tokenadd_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset(Token::getMain()->getConfig()->get("tokenadd")[2])) {
            if (!$sender->hasPermission(Token::getMain()->getConfig()->get("tokenadd")[2])) {
                $sender->sendMessage(Token::getMain()->getConfig()->get("no_perm"));
                return;
            }
        }

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (Token::getMain()->existToken($name)) {
                if (isset($args[1])) {
                    if (is_numeric($args[1])) {
                        Token::getMain()->addToken($name, $args[1]);
                        $sender->sendMessage(str_replace(["{token}", "{player}"], [$args[1], $name], Token::getMain()->getConfig()->get("tokenadd_msg")));
                    } else $sender->sendMessage(Token::getMain()->getConfig()->get("no_numeric_token"));
                } else $sender->sendMessage(Token::getMain()->getConfig()->get("no_args_token"));
            } else $sender->sendMessage(Token::getMain()->getConfig()->get("no_exist_player"));
        } else $sender->sendMessage(Token::getMain()->getConfig()->get("no_args_player"));
    }
}