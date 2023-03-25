<?php

namespace JackMD\ScoreHud\Addons
{
    use JackMD\ScoreHud\addon\AddonBase;
    use pocketmine\Player;
    use Vote\VoteMain;

    class VoteAddon extends AddonBase{
        private $vote;
        public function onEnable(): void{
            $this->vote = $this->getServer()->getPluginManager()->getPlugin("Vote");
        }

        public function getProcessedTags(Player $player): array{
            return [
                "{vote}" => $this->vote->getVote()
            ];
        }
    }
}