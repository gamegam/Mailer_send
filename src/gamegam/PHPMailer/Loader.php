<?php

namespace gamegam\PHPMailer;

use pocketmine\plugin\PluginBase;

class Loader extends PluginBase{

    public function onEnable(): void{
        // config
        $this->saveDefaultConfig();
        // cmd
        $this->getServer()->getCommandMap()->register($this->getName(), new PHPMailerCommand($this));
        $this->saveResource("index.html", true);
    }
}