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
		"prefix" => "§a§l[ §f서버 §a] "
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
		if ($event->getPlayer()->isOp()) {
			$this->getServer()->broadcastMessage ($this->db["prefix"] . $this->Replace($event->getPlayer()->getName(), $this->db["OP-Join-msg"]));
		} else {
			$this->getServer()->broadcastPopup ($this->Replace($event->getPlayer()->getName(), $this->db["USER-Join-msg"]));
		}
	}
	public function onQuit(PlayerQuitEvent $event) {
		$event->setQuitMessage(false);
		if ($event->getPlayer()->isOp()) {
			$this->getServer()->broadcastMessage ($this->db["prefix"] . $this->Replace($event->getPlayer()->getName(), $this->db["OP-Quit-msg"]));
		} else {
			$this->getServer()->broadcastPopup ("§e[ §f로그아웃§e ] §f" . $this->Replace($event->getPlayer()->getName(), $this->db["USER-Quit-msg"]));
		}
	}
}
?>
