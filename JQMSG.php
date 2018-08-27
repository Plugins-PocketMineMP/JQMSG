<?php

/**
 * @name JQMSG
 * @author alvin0319
 * @main alvin0319\JQMSG
 * @version Rewrite-1.0.1
 * @api 4.0.0
 */
namespace alvin0319;
//한글아깨지지마렴^_^

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\{
	PlayerJoinEvent, PlayerQuitEvent
};
use pocketmine\utils\Config;

class JQMSG extends PluginBase implements Listener {
	public function onEnable() {
		@mkdir ($this->getDataFolder());
		$this->config = new Config($this->getDataFolder() . "Messages.yml", Config::YAML, [
		"OP-Join-msg" => "§c관리자§f {이름}§c님이 §a서버에§f 접속하셨습니다",
		"USER-Join-msg" => "§e[ §f로그인§e ] §f{이름}",
		"OP-Quit-msg" => "§c관리자§f {이름}§f님이 서버에서 퇴장하셨습니다",
		"USER-Quit-msg" => "§e[ §f로그아웃§e ] §f{이름}",
		"prefix" => "§a§l[ §f서버 §a] ",
		"No-has-player-data" => "{이름}님이 서버에 처음 접속하셨습니다!",
		"OP-Logger" => false
		]);
		$this->db = $this->config->getAll();
		$this->getServer()->getPluginManager()->registerEvents ($this, $this);
		$this->getLogger()->info ("§cJQMSG 버전 Rewrite-1.0.0 이 활성화되었습니다");
	}
	public function Replace($player, $msg) {
		return str_replace ("{이름}", $player, $msg);
	}
	public function onJoin(PlayerJoinEvent $event) {
		$event->setJoinMessage(false);
		$count = count($this->getServer()->getOnlinePlayers());
		$this->getServer()->getLogger()->info ("§e" . $event->getPlayer()->getName() . "님 입장\n현재인원: " . $count . "명");
		if ($event->getPlayer()->isOp()) {
			$this->getServer()->broadcastMessage ($this->db["prefix"] . $this->Replace($event->getPlayer()->getName(), $this->db["OP-Join-msg"]));
		} else {
			$this->getServer()->broadcastPopup ($this->Replace($event->getPlayer()->getName(), $this->db["USER-Join-msg"]));
		}
		if (! $event->getPlayer()->hasPlayedBefore()) {
		    $this->getServer()->broadcastMessage ($this->db["prefix"] . $this->Replace($event->getPlayer()->getName(), $this->db["No-has-player-data"]));
		}
		if ($this->db["OP-Logger"] === true) {
		    foreach ($this->getServer()->getOnlinePlayers() as $op) {
		        if ($op->isOp()) {
		            $op->sendMessage ("§e" . $event->getPlayer()->getName() . "입장\n현재인원: " . $count . "명");
		        }
		    }
		}
	}
	public function onQuit(PlayerQuitEvent $event) {
		$event->setQuitMessage(false);
		$count = count($this->getServer()->getOnlinePlayers());
		$this->getServer()->getLogger()->info ("§e" . $event->getPlayer()->getName() . "님 퇴장\n현재인원: " . $count . "명");
		if ($event->getPlayer()->isOp()) {
			$this->getServer()->broadcastMessage ($this->db["prefix"] . $this->Replace($event->getPlayer()->getName(), $this->db["OP-Quit-msg"]));
		} else {
			$this->getServer()->broadcastPopup ("§e[ §f로그아웃§e ] §f" . $this->Replace($event->getPlayer()->getName(), $this->db["USER-Quit-msg"]));
		}
		if ($this->db["OP-Logger"] === true) {
		    foreach ($this->getServer()->getOnlinePlayers() as $op) {
		        if ($op->isOp()) {
		            $op->sendMessage ("§e" . $event->getPlayer()->getName() . "퇴장\n현재인원: " . $count . "명");
		        }
		    }
		}
	}
}
