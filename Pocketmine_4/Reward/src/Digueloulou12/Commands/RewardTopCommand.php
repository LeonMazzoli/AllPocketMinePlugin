<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\command\Command;
use Digueloulou12\Reward;

class RewardTopCommand extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $sender->sendMessage(Reward::getInstance()->getConfigValue("title_top"));

        $array = [];
        foreach (Reward::$data->getAll() as $player => $key) {
            $array[$player] = $key["day"];
        }
        arsort($array);

        $i = 1;
        foreach ($array as $player => $value) {
            if ($i !== 11) {
                $sender->sendMessage(str_replace(["{place}", "{name}", "{day}"], [$i, $player, $value], Reward::getInstance()->getConfigValue("top_msg")));
                $i++;
            } else break;
        }
    }
}