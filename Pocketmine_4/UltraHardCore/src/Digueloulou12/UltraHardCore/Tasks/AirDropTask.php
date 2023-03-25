<?php

namespace Digueloulou12\UltraHardCore\Tasks;

use Digueloulou12\UltraHardCore\Entity\AirDropEntity;
use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class AirDropTask extends Task
{
    public function onRun(): void
    {
        $data = $this->pngToBytes(UltraHardCore::getInstance()->getDataFolder() . Utils::getConfigValue("airdrop_skin_file"));
        $data_ = file_get_contents(UltraHardCore::getInstance()->getDataFolder() . Utils::getConfigValue("geometry_file"));
        $skin = new Skin("AirDrop", $data, "", Utils::getConfigValue("geometry_name"), $data_);

        for ($count = intval(Utils::getConfigValue("number_airdrop")); $count !== 0; $count--) {
            $x = mt_rand(Utils::getConfigValue("random_x_min"), Utils::getConfigValue("random_x_max"));
            $z = mt_rand(Utils::getConfigValue("random_z_min"), Utils::getConfigValue("random_z_max"));
            $world = Server::getInstance()->getWorldManager()->getWorldByName(Utils::getConfigValue("game_world"));
            $location = new Location($x, 200, $z, $world, 0, 0);
            var_dump($location);
            $entity = new AirDropEntity($location, $skin);
            $entity->spawnToAll();
        }
    }

    public function pngToBytes(string $path): string
    {
        $img = @imagecreatefrompng($path);
        $bytes = "";
        for ($y = 0; $y < (int)@getimagesize($path)[1]; $y++) {
            for ($x = 0; $x < (int)@getimagesize($path)[0]; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $bytes .= chr(($rgba >> 16) & 0xff) . chr(($rgba >> 8) & 0xff) . chr($rgba & 0xff) . chr(((~((int)($rgba >> 24))) << 1) & 0xff);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }
}