<?php

namespace Digueloulou12\API;

use pocketmine\level\Level;
use pocketmine\Server;
use pocketmine\utils\AssumptionFailedError;

class WorldAPI
{
    public static function duplicateLevel(string $levelName, string $duplicateName): void
    {
        if (Server::getInstance()->isLevelLoaded($levelName)) {
            self::getLevelByNameNonNull($levelName)->save(false);
        }

        mkdir(Server::getInstance()->getDataPath() . "/worlds/$duplicateName");

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(Server::getInstance()->getDataPath() . "/worlds/$levelName", \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $fileInfo) {
            if ($filePath = $fileInfo->getRealPath()) {
                if ($fileInfo->isFile()) {
                    copy($filePath, str_replace($levelName, $duplicateName, $filePath));
                } else mkdir(str_replace($levelName, $duplicateName, $filePath));
            }
        }
    }

    public static function getLevelByNameNonNull(string $name): Level
    {
        $level = Server::getInstance()->getLevelByName($name);
        if ($level === null) {
            throw new AssumptionFailedError("Required level $name is null");
        }
        return $level;
    }

    public static function removeLevel(string $name)
    {
        if (Server::getInstance()->isLevelLoaded($name)) {
            $level = self::getLevelByNameNonNull($name);
            if (count($level->getPlayers()) > 0) {
                foreach ($level->getPlayers() as $player) {
                    $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                }
            }
            Server::getInstance()->unloadLevel($level);
        }

        $removedFiles = 1;

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($worldPath = Server::getInstance()->getDataPath() . "/worlds/$name", \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        /** @var \SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            if ($filePath = $fileInfo->getRealPath()) {
                if ($fileInfo->isFile()) {
                    unlink($filePath);
                } else {
                    rmdir($filePath);
                }

                $removedFiles++;
            }
        }

        rmdir($worldPath);
    }
}