<?php

namespace Zeon\CoinFlip\Forms;

use Digueloulou12\Money;
use pocketmine\player\Player;
use pocketmine\Server;
use Zeon\CoinFlip\libs\jojoe77777\FormAPI\CustomForm;
use Zeon\CoinFlip\libs\jojoe77777\FormAPI\SimpleForm;

class CoinFlipForms
{
    private int $minMoney = 1000;
    public static array $coinFlips = [];

    public static function coinForm(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if (is_null($data)) return;

            if ($data === 0) {
                if (!isset(self::$coinFlips[$player->getName()])) {
                    self::createCoinFlipForm($player);
                } else $player->sendMessage("Vous avez déja créé un CoinFlip !");
                return;
            }

            if (count(self::$coinFlips) > 0) {
                self::listCoinFlipForm($player);
            } else $player->sendMessage("Il n'y a aucun CoinFlip en cour !");
        });
        $form->setTitle("CoinFlip");
        $form->addButton("Créer");
        $form->addButton("Rejoindre");
        $form->sendToPlayer($player);
    }

    public static function createCoinFlipForm(Player $player): void
    {
        $form = new CustomForm(function (Player $player, array $data = null) {
            if (is_null($data)) return;

            $money = $data[0];
            if ($money !== "") {
                if (ctype_alnum($money)) {
                    if (Money::getMoneyPlayer($player) >= $money) {
                        if ($money >= $this->minMoney) {
                            Money::removeMoney($player, $money);
                            Server::getInstance()->broadcastMessage("{$player->getName()} a créé un CoinFlip de {$money}$ !");
                            self::$coinFlips[$player->getName()] = $money;
                        } else $player->sendMessage("Vous devez indiquer un montant supérieur à {$this->minMoney} !");
                    } else $player->sendMessage("Vous n'avez pas l'argent nécessaire !");
                } else $player->sendMessage("Vous devez indiquer un montant !");
            } else $player->sendMessage("Vous devez indiquer un montant !");
        });
        $form->setTitle("CoinFlip");
        $form->addInput("Montant");
        $form->sendToPlayer($player);
    }

    public static function listCoinFlipForm(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data = null){
            if (is_null($data)) return;

            if (isset(self::$coinFlips[$data])) {
                $money = self::$coinFlips[$data];
                if (Money::getMoneyPlayer($player) >= $money) {
                    Money::removeMoney($player, $money);
                    $winner = mt_rand(0, 1) > 0 ? $data : $player->getName();
                    $loser = $winner === $data ? $player->getName() : $data;
                    Money::addMoney($winner, $money*2);
                    $pl = Server::getInstance()->getPlayerExact($winner);
                    if ($pl instanceof Player) $pl->sendMessage("Vous avez gagner le CoinFlip !");
                    $pl = Server::getInstance()->getPlayerExact($loser);
                    if ($pl instanceof Player) $pl->sendMessage("Vous avez perdu le CoinFlip !");
                    unset(self::$coinFlips[$data]);
                } else $player->sendMessage("Vous n'avez pas assez d'argent !");
            } else $player->sendMessage("Le CoinFlip de $data n'existe plus !");
        });
        $form->setTitle("CoinFlip");
        foreach (self::$coinFlips as $pl => $money) {
            $form->addButton("$pl -> $money", SimpleForm::IMAGE_TYPE_NULL, "", $pl);
        }
        $form->sendToPlayer($player);
    }
}