<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Fly extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "fly", "Fly in Survival or Adventure mode!", "[player]");
        $this->setPermission("essentials.fly.use");
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
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                $this->getPlugin()->switchCanFly($sender);
                $sender->sendMessage(TextFormat::YELLOW . "Flying mode " . ($this->getPlugin()->canFly($sender) ? "enabled" : "disabled") . "!");
                return true;
            case 1:
                if(!$sender->hasPermission("essentials.fly.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getPlugin()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $this->getPlugin()->switchCanFly($player);
                $player->sendMessage(TextFormat::YELLOW . "Flying mode " . ($this->getPlugin()->canFly($player) ? "enabled" : "disabled") . "!");
                $sender->sendMessage(TextFormat::YELLOW . "Flying mode " . ($this->getPlugin()->canFly($player) ? "enabled" : "disabled") . " for " . $player->getDisplayName());
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}