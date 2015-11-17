<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class More extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "more", "Get a stack of the item you're holding", null, false);
        $this->setPermission("essentials.more");
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "[Error] You're in " . $this->getPlugin()->getServer()->getGamemodeString($gm) . " mode");
            return false;
        }
        if(count($args) != 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $item = clone($sender->getInventory()->getItemInHand());
        if($item->getID() === 0){
            $sender->sendMessage(TextFormat::RED . "You can't get a stack of AIR");
            return false;
        }
        $item->setCount(($sender->hasPermission("essentials.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize()));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage(TextFormat::AQUA . "Filled up the item stack to " . $item->getCount());
        return true;
    }
}
