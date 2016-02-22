<?php
		/* Fill those with your data */
        $_BOT_TOKEN = "";
		$_REPORT_CHAT_ID = 0;
		$_ALLOW_COMMAND_USER = 0;
		$_LOG_PATH = "/var/www/yoursite/logs/error.log";
		
		
        $_API_URL = "https://api.telegram.org/bot" . $_BOT_TOKEN . "/";		
        include("lib/Telegram.php");
        $telegram = new Telegram($_BOT_TOKEN);
        $chat_id = $telegram->ChatID();
		$msg_id = $telegram->MsgId();
        $received = $telegram->Text();
        $firstname = $telegram->FirstName();
        //$lastname = $telegram->LastName();
        $username = $telegram->Username();
		$sender_id = $telegram->SenderId();

		
		
		//If the bot receives a "/me" message, output some info
		if($received === "/me") {			
			$msg = "chat_id= " . $chat_id . "\nsender_id= " . $sender_id . "\nusername= " . $username . "\nfirstname= " . $firstname;
			$content = array('chat_id' => $chat_id, 'text' => $msg);
			$telegram->sendMessage($content);
		}
		
		if($received === "/tail" && $sender_id == $_ALLOW_COMMAND_USER) {			
			$tail = exec('tail ' . $_LOG_PATH . ' -n 10');
			$content = array('chat_id' => $chat_id, 'text' => $tail);
			$telegram->sendMessage($content);
		}

        //Tail the PHP error.log
		$tail = exec('tail ' . $_LOG_PATH . ' -n 1');
		$last_tail = file_get_contents("./last.log");
		
		if($tail !== $last_tail) {
			file_put_contents("./last.log", $tail);
			$content = array('chat_id' => $_REPORT_CHAT_ID, 'text' => $tail);
			$telegram->sendMessage($content);			
		}
		
?>
