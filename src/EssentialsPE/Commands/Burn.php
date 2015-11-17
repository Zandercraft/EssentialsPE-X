<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Burn extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "burn", "Set a player on fire", "<player> <seconds>");
        $this->setPermission("essentials.burn");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) != 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $time = $args[1];
        if(!($player = $this->getPlugin()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        if(!is_numeric($time)){
            $sender->sendMessage(TextFormat::RED . "[Error] Invalid burning time");
        }else{
            $player->setOnFire($time);
            $sender->sendMessage(TextFormat::YELLOW . $player->getDisplayName() . " is now on fire!");
        }
        return true;
    }
}
