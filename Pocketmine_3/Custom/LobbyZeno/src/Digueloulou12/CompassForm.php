<?php

namespace Digueloulou12;

use Digueloulou12\libraries\MinecraftQuery;
use Digueloulou12\libraries\MinecraftQueryException;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class CompassForm{
    public static function form(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data){
                case 0:
                    self::connect("142.44.253.217", "5657",$player);
                    break;
                case 1:
                    self::connect("79.137.105.165", "5657", $player);
                    break;
            }
        });

        $query = new MinecraftQuery();

        $pna = 0;
        $maxna = 0;
        if (self::ping("142.44.253.217", 5657)){
            try {
                $query->Connect("142.44.253.217", 5657, 0);
                $array = ($query->GetInfo());

                $pna = $array['Players'];
                $maxna = $array['MaxPlayers'];
            } catch (MinecraftQueryException $e) {
                Server::getInstance()->getLogger()->critical($e->getMessage());
            }
        }

        $peu = 0;
        $maxeu = 0;
        if (self::ping("79.137.105.165", 5657)){
            try {
                $query->Connect("79.137.105.165", 5657, 0);
                $array = ($query->GetInfo());

                $peu = $array['Players'];
                $maxeu = $array['MaxPlayers'];
            } catch (MinecraftQueryException $e) {
                Server::getInstance()->getLogger()->critical($e->getMessage());
            }
        }

        $form->setTitle("Region selector");
        $form->addButton("Zeno NA [$pna/$maxna]");
        $form->addButton("Zeno EU [$peu/$maxeu]");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function ping($host, $port, $timeout = 100): bool
    {
        try {
            fsockopen($host, $port, $php_errorcode, $php_errormsg, $timeout);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function connect($host, $port, Player $player)
    {
        if (self::ping($host, $port)) {
            $player->transfer($host, $port);
        } else $player->sendMessage("§4You can't connect on this server");
    }
}