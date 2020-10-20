<?php

namespace Scoreboards\API;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;

class Scoreboards
{

    public $lines = [];
    public $score = [];

    public function sendScore(Player $player, string $title, int $order)
    {
        if (!isset($this->score[$player->getName()]))
        {
            $this->setRemove($player);
        }
            $packet = new SetDisplayObjectivePacket();
            $packet->objectiveName = $player->getName();
            $packet->displaySlot = "sidebar";
            $packet->displayName = $title;
            $packet->criteriaName = "dummy";
            $packet->sortOrder = $order;
            $player->getNetworkSession()->sendDataPacket($packet);
            $this->score[$player->getName()] = $player->getName();
    }

    public function setLine(Player $player, int $lines, string $text)
    {
        $this->lines[$lines] = $text;
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $player->getName();
        $entry->customName = $text;
        $entry->score = $lines;
        $entry->scoreboardId = $lines;
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $packet = new SetScorePacket();
        $packet->type = SetScorePacket::TYPE_CHANGE;
        $packet->entries[$lines] = $entry;
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public function setLines(Player $player, array $lines)
    {
        foreach ($lines as $line => $text)
        {
            $this->setLine($player, $line, $text);
        }
    }

    public function setRemove(Player $player)
    {
        if (isset($this->score[$player->getName()]))
        {
            $packet = new RemoveObjectivePacket();
            $packet->objectiveName = $player->getName();
            $player->getNetworkSession()->sendDataPacket($packet);
            unset($this->score[$player->getName()]);
        }
    }
}
?>
