<?php

namespace Digueloulou12\PaperMoney;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class PaperMoney extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "bank") {
            if ($sender instanceof Player) {
                if (isset($args[0]) and ctype_digit($args[0])) {
                    if (EconomyAPI::getInstance()->myMoney($sender) >= $args[0]) {
                        $item = VanillaItems::PAPER();
                        $item->getNamedTag()->setInt("money", $args[0]);
                        $item->setCustomName(str_replace("{money}", $args[0], $this->getConfig()->get("customName")));
                        if ($sender->getInventory()->canAddItem($item)) {
                            $sender->getInventory()->addItem($item);
                        } else $sender->dropItem($item);
                        EconomyAPI::getInstance()->reduceMoney($sender, $args[0]);
                    } else $sender->sendMessage($this->getConfig()->get("no_money"));
                } else $sender->sendMessage($this->getConfig()->get("no_args"));
            }
            return true;
        }
        return false;
    }

    public function onUse(PlayerItemUseEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item->getId() === ItemIds::PAPER) {
            $money = $item->getNamedTag()->getTag("money");
            if ($money instanceof IntTag) {
                EconomyAPI::getInstance()->addMoney($player, $money->getValue());
                $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
            }
        }
    }
}