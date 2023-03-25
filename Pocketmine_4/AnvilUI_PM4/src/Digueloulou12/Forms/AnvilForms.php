<?php

namespace Digueloulou12\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Digueloulou12\AnvilMain;
use pocketmine\item\Armor;
use pocketmine\item\Tool;

class AnvilForms
{
    public static function mainForm(Player $player): SimpleForm
    {
        $config = new Config(AnvilMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($config) {
            switch ($data) {
                case 0:
                    $item = $player->getInventory()->getItemInHand();
                    if (($item instanceof Tool) or ($item instanceof Armor)) {
                        if ($item->getMeta() !== 0) {
                            if ($player->getXpManager()->getXpLevel() >= $config->get("xp_repair")) {
                                $item->setDamage(0);
                                $player->getInventory()->setItemInHand($item);
                                $player->getXpManager()->subtractXpLevels($config->get("xp_repair"));
                                $player->sendMessage($config->get("repair_good"));
                            } else $player->sendMessage($config->get("noxprepair"));
                        } else $player->sendMessage($config->get("no_repair_meta"));
                    } else $player->sendMessage($config->get("no_repair"));
                    break;
                case 1:
                    self::renameForm($player);
                    break;
            }
        });
        $form->setTitle($config->get("main")["title"]);
        $form->setContent($config->get("main")["content"]);
        $form->addButton($config->get("main")["repair"]);
        $form->addButton($config->get("main")["rename"]);
        $form->sendToPlayer($player);
        return $form;
    }

    public static function renameForm(Player $player): CustomForm
    {
        $config = new Config(AnvilMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new CustomForm(function (Player $player, array $data = null) use ($config) {
            if ($data === null) {
                $player->sendMessage($config->get("rename_null"));
                return;
            }
            if ($player->getXpManager()->getXpLevel() >= $config->get("xp_rename")) {
                $item = $player->getInventory()->getItemInHand();
                $item->setCustomName($data[1]);
                $player->getInventory()->setItemInHand($item);
                $player->getXpManager()->subtractXpLevels($config->get("xp_rename"));
                $player->sendMessage(str_replace(strtolower("{name}"), $data[1], $config->get("rename_good")));
            } else $player->sendMessage($config->get("no_xp_rename"));

        });
        $form->setTitle($config->get("rename")["title"]);
        $form->addLabel($config->get("rename")["content"]);
        $form->addInput($config->get("rename")["input"]);
        $form->sendToPlayer($player);
        return $form;
    }
}