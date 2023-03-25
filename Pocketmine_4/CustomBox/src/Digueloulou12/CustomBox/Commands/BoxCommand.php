<?php

namespace Digueloulou12\CustomBox\Commands;

use Digueloulou12\CustomBox\API\SkinAPI;
use Digueloulou12\CustomBox\BoxEntity\BoxEntity;
use Digueloulou12\CustomBox\CustomBox;
use Digueloulou12\CustomBox\Utils\Utils;
use JsonException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class BoxCommand extends Command
{
    public static array $players = [];

    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, null, $aliases);
        $this->setPermission("box.use");
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                if (isset($args[0])) {
                    $name = Utils::getConfigValue("boxs")[$args[0]] ?? null;
                    if (strtolower($args[0]) === "remove") {
                        self::$players[] = $sender->getName();
                        $sender->sendMessage(Utils::getConfigReplace("indication_remove"));
                    } elseif (!is_null($name)) {
                        $data = SkinAPI::pngToBytes(CustomBox::getInstance()->getDataFolder() . $name["skin"]["texture"]);
                        $data_ = file_get_contents(CustomBox::getInstance()->getDataFolder() . $name["skin"]["geometry"]);
                        $skin = new Skin("BoxEntity", $data, "", $name["skin"]["geometryId"], $data_);
                        $entity = new BoxEntity($sender->getLocation(), $skin, (new CompoundTag())->setString("boxName", $args[0])->setString("NameTag", $name["nametag"]));
                        $entity->spawnToAll();
                        $sender->sendMessage(Utils::getConfigReplace("spawn_box"));
                    } else $sender->sendMessage(Utils::getConfigReplace("exist_name"));
                } else $sender->sendMessage(Utils::getConfigReplace("exist_name"));
            } else $sender->sendMessage(Utils::getConfigReplace("no_permission"));
        }
    }
}