<?php

namespace Digueloulou12\LobbyCore\API;

class ServerInfo
{
    private array $infos;
    private string $ip;
    private int $port;

    public function __construct(string $ip, int $port)
    {
        $this->ip = $ip;
        $this->port = $port;

        $this->infos = $this->getServerInfos();
    }

    private function getServerInfos(): array
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        return (array)json_decode(file_get_contents("https://api.mcsrvstat.us/bedrock/2/" . $this->getIp() . ":" . $this->getPort(), false, stream_context_create($arrContextOptions)),true);
    }

    public function getOnlinePlayers(): int
    {
        return $this->infos["players"]["online"] ?? 0;
    }

    public function getMaxPlayers(): int
    {
        return $this->infos["players"]["max"] ?? 0;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    private function objectToArray($d): array
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }
}