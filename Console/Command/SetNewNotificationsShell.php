<?php

class SetNewNotificationsShell extends AppShell {

	public $uses = array('User');

	public function main() {
		$this->User->query("UPDATE Users SET new_notifications = 1;");
	}
}