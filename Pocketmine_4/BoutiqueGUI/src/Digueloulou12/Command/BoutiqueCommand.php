<?php

namespace Digueloulou12\Command;

use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\console\ConsoleCommandSender;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\command\Command;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use muqsit\invmenu\InvMenu;
use Digueloulou12\Boutique;
use pocketmine\Server;

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
            $this->sendInventory($sender, $config);
        }
    }

    public function sendInventory(Player $player, Config $config)
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName($config->get("title"));
        foreach ($config->get("rank") as $rank => $key) {
            $item = explode("-", $key["item"]);
            $itemc = ItemFactory::getInstance()->get($item[1], $item[2], 1)->setCustomName($key["button"]);
            $itemc->setCustomName($itemc->getCustomName() . "\n" . $key["content"]);
            $itemc->getNamedTag()->setString("rank", $rank);
            $menu->getInventory()->setItem($item[0], $itemc);
        }
        $menu->setListener(function (InvMenuTransaction $transaction) use ($config, $player): InvMenuTransactionResult {
            $token = Server::getInstance()->getPluginManager()->getPlugin("Token");
            $d = new Config(Boutique::getMain()->getDataFolder() . "Data.json", Config::JSON);
            if ($transaction->getItemClicked()->getNamedTag()->getTag("rank") !== null) {
                $rank = $transaction->getItemClicked()->getNamedTag()->getString("rank");
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
            }

            return $transaction->discard();
        });
        $menu->send($player);
    }
}