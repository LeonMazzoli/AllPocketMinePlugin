<?php

namespace Digueloulou12\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Digueloulou12\Storage;

class StorageForms
{
    public static function mainForm(Player $player, string $block): SimpleForm
    {
        $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($block, $storage) {
            if ($data === null) return;

            if ($storage->exists($block)) {
                switch ($data) {
                    case 0:
                        self::addXp($player, $block);
                        break;
                    case 1:
                        self::removeXp($player, $block);
                        break;
                }
            } else $player->sendMessage((string)Storage::getConfigValue("no_storage"));
        });
        $form->setTitle(Storage::getConfigValue("title"));
        $form->setContent(Storage::getConfigValue("content"));
        $form->addButton(Storage::getConfigValue("add"));
        $form->addButton(Storage::getConfigValue("remove"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function addXp(Player $player, string $block): CustomForm
    {
        $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($block, $storage) {
            if ($data === null) return;

            if ($storage->exists($block)) {
                if ($data[1] <= $player->getXpManager()->getCurrentTotalXp()) {
                    $player->getXpManager()->subtractXp($data[1]);
                    $storage->set($block, $storage->get($block) + $data[1]);
                    $storage->save();
                    $player->sendMessage(str_replace("{xp}", $data[1], (string)Storage::getConfigValue("add_xp")));
                } else $player->sendMessage((string)Storage::getConfigValue("no_xp"));
            } else $player->sendMessage((string)Storage::getConfigValue("no_storage"));
        });
        $form->setTitle(Storage::getConfigValue("title"));
        $form->addLabel(Storage::getConfigValue("label"));
        $form->addSlider(Storage::getConfigValue("slider"), 1, $player->getXpManager()->getCurrentTotalXp());
        $form->sendToPlayer($player);
        return $form;
    }

    public static function removeXp(Player $player, string $block): CustomForm
    {
        $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($block, $storage) {
            if ($data === null) return;

            if ($storage->exists($block)) {
                if ($storage->get($block) >= $data[1]) {
                    $player->getXpManager()->addXp($data[1]);
                    $storage->set($block, $storage->get($block) - $data[1]);
                    $storage->save();
                    $player->sendMessage(str_replace("{xp}", $data[1], (string)Storage::getConfigValue("remove_xp")));
                } else $player->sendMessage(str_replace("{xp}", $storage->get($block), (string)Storage::getConfigValue("no_remove")));
            } else $player->sendMessage((string)Storage::getConfigValue("no_storage"));
        });
        $form->setTitle(Storage::getConfigValue("title"));
        $form->addLabel(Storage::getConfigValue("label"));
        $form->addSlider(Storage::getConfigValue("slider"), 1, $storage->get($block));
        $form->sendToPlayer($player);
        return $form;
    }
}