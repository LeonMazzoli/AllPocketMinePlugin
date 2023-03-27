<?php

namespace Zeon\CoinFlip;

use Digueloulou12\Money;
use pocketmine\plugin\PluginBase;
use Zeon\CoinFlip\Command\CoinFlipCommand;
use Zeon\CoinFlip\Forms\CoinFlipForms;

class CoinFlip extends PluginBase
{
    public function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register("CoinFlip", new CoinFlipCommand());
    }

    public function onDisable(): void
    {
        foreach (CoinFlipForms::$coinFlips as $player => $money) {
            Money::addMoney($player, $money);
        }
    }
}