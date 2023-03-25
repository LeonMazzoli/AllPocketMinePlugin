<?php

namespace Digueloulou12\Command;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use Digueloulou12\Boutique;
use pocketmine\Server;
use pocketmine\utils\Config;

class BoutiqueCommand extends Command
{
    public function __construct()
    {
        parent::__construct(Boutique::getMain()->getConfig()->get("command")[0]);
        if (isset(Boutique::getMain()->getConfig()->get("command")[1])) $this->setDescription(Boutique::getMain()->getConfig()->get("command")[1]);
        $this->setAliases(Boutique::getMain()->getConfig()->get("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = Boutique::getMain()->getConfig();

        if (isset($config->get("command")[2])) {
            if (!$sender->hasPermission($config->get("command")[2])) {
                $sender->sendMessage($config->get("no_perm"));
                return;
            }
        }

        if ($sender instanceof Player) {
            $form = new SimpleForm(function (Player $player, $data = null) use ($config) {
                if (($data !== null) and ($data !== "back")) {
                    $rank = $data;
                    $form = new SimpleForm(function (Player $player, int $data = null) use ($config, $rank) {
                        if ($data === null) return;

                        $token = Server::getInstance()->getPluginManager()->getPlugin("Token");
                        $d = new Config(Boutique::getMain()->getDataFolder() . "Data.json", Config::JSON);
                        switch ($data) {
                            case 0:
                                if (!$d->exists($player->getName()) or !in_array($rank, $d->get($player->getName()))) {
                                    if ($token->getToken($player) >= $config->get("rank")[$rank]["cost"]) {
                                        $token->removeToken($player, $config->get("rank")[$rank]["cost"]);
                                        if ($d->exists($player->getName())) $array = $d->get($player->getName()); else $array = [];
                                        $array[] = $rank;
                                        $d->set($player->getName(), $array);
                                        $d->save();
                                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), new Language(Language::FALLBACK_LANGUAGE)), str_replace("{player}", $player->getName(), $config->get("rank")[$rank]["command_buy"]));
                                    } else $player->sendMessage($config->get("not_has_just_token"));
                                } else $player->sendMessage($config->get("already"));
                                break;
                        }
                    });
                    $form->setTitle($config->get("title"));
                    $form->setContent($config->get("rank")[$rank]["content"]);
                    $form->addButton($config->get("buy_button"));
                    $form->addButton($config->get("button"));
                    $form->sendToPlayer($player);
                }
            });
            $form->setTitle($config->get("title"));
            $form->setContent($config->get("content"));
            foreach ($config->get("rank") as $rank => $key) {
                $form->addButton($key["button"], -1, "", $rank);
            }
            $form->addButton($config->get("button"), -1, "", "back");
            $form->sendToPlayer($sender);
        }
    }
}