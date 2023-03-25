<?php

namespace Digueloulou12\Forms;

use pocketmine\console\ConsoleCommandSender;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use Digueloulou12\Reward;
use pocketmine\Server;

class RewardForms
{
    public static function mainForm(string $name): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) return;

            if (!Reward::$data->get($player->getName())["loot"]) {
                foreach (Reward::getInstance()->getConfigValue("day")[Reward::$data->get($player->getName())["day"] - 1] as $loot) {
                    $explode = explode(":", $loot);
                    if ($explode[0] === "item") {
                        $item = explode("-", $explode[1]);
                        $items = ItemFactory::getInstance()->get($item[0], $item[1], $item[2]);
                        if ($player->getInventory()->canAddItem($items)) {
                            $player->getInventory()->addItem($items);
                        } else $player->getWorld()->dropItem($player->getPosition(), $items);
                    } elseif ($explode[0] === "command") {
                        $cmd = explode("-", $explode[0]);
                        if ($cmd[0] === "server") {
                            Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace("{player}", $player->getName(), $cmd[1]));
                        } elseif ($cmd[0] === "player") Server::getInstance()->getCommandMap()->dispatch($player, $cmd[1]);
                    }
                }
                Reward::$data->set($player->getName(), ["day" => Reward::$data->get($player->getName())["day"], "max_day" => Reward::$data->get($player->getName())["max_day"], "loot" => true]);
                Reward::$data->save();
                $player->sendMessage(str_replace("{day}", Reward::$data->get($player->getName())["day"], Reward::getInstance()->getConfigValue("collected")));
            } else $player->sendMessage(Reward::getInstance()->getConfigValue("already"));
        });
        $form->setTitle(Reward::getInstance()->getConfigValue("title"));
        $form->setContent(str_replace("{day}", Reward::$data->get($name)["day"], Reward::getInstance()->getConfigValue("content")));
        $form->addButton(Reward::getInstance()->getConfigValue("button"));
        return $form;
    }
}