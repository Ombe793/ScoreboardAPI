<?php

namespace Scoreboards;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use Scoreboards\API\Scoreboards;

class ScorePE extends PluginBase {

    public static $instance = null;
    public $scoreboars = null;

    public function onEnable()
    {
        $this->getLogger()->info(TextFormat::DARK_AQUA." Activada");
        $this->scoreboars = new Scoreboards($this);
        self::$instance = $this;
    }

    public static function getInstance() : ScorePE
    {
        return self::$instance;
    }

    public function getScoreboards() : Scoreboards
    {
        return $this->scoreboars;
    }
 }
 ?>
