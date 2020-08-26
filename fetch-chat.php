<?php
require_once 'init.php';
if (!$currentuser) {
   header('Location: index.php');
   exit();
}

mysqli_set_charset($conn, 'utf8mb4');

if( isset($_REQUEST['action']) ){

	switch( $_REQUEST['action'] ){

		case "SendMessage":
			session_start();

			$query_chat = $db->prepare("INSERT INTO teamx_chat SET fromUserID = ?, toUserID = ?, message = ?");
			$query_chat->execute([$_SESSION['userId'], $_REQUEST['toUserID'] , $_REQUEST['message_chat']]);

			$query_chk = $db->prepare("SELECT * FROM teamx_notify WHERE n_type = ? AND forUserID = ? AND fromUserID = ?");
			$query_chk->execute([3, $_REQUEST['toUserID'] , $_SESSION['userId']]);
			$rs_chk = $query_chk->fetch(PDO::FETCH_ASSOC);

			if($rs_chk){
				$stmt = $db->prepare("DELETE FROM teamx_notify WHERE n_type = ? AND forUserID = ? AND fromUserID = ?");
				$stmt->execute([3, $_REQUEST['toUserID'] , $_SESSION['userId']]);
			}

			$query_n = $db->prepare("INSERT INTO teamx_notify SET n_type = ?, forUserID = ?, fromUserID = ?");
			$query_n->execute([3, $_REQUEST['toUserID'] , $_SESSION['userId']]);

			echo 1;
		break;

		case "getChat":

			$query = $db->prepare("SELECT * FROM teamx_chat WHERE (fromUserID = ? AND toUserID = ?) OR (fromUserID = ? AND toUserID = ?)");
			$query->execute([$_REQUEST['fromUserID'], $_REQUEST['toUserID'] , $_REQUEST['toUserID'], $_REQUEST['fromUserID']]);

			$rs = $query->fetchAll(PDO::FETCH_OBJ);

			$chat = '';
			foreach( $rs as $r ){

				if(file_exists('./files/images/avatars/'.$r->fromUserID.'.jpg')){
					$avatar = './files/images/avatars/'.$r->fromUserID.'.jpg';
				}
				else{
					$avatar = './files/images/avatars/0.jpg';
				}

				$_date = date_create($r->createdAt);
				$date = date_format($_date, 'd-m-Y H:i:s');
				$datex = date_format($_date, 'd-m-Y');
				$dateH = date_format($_date, 'H');

				$_datesrt = date_create($r->createdAt);
				$datesrt = date_format($_datesrt, 'H:i:s');

				if(($datex != date("d-m-Y")) && $dateH != date("H")){
					$datByday = '<div class="chat-box-single-line"><small class="timestamp text-muted">'.$date.'</small></div>';
				}
				else{
					$datByday = '';
				}

				if($r->fromUserID == $currentuser['userID']){
					$chat .=  ''.$datByday.'<div class="direct-chat-msg doted-border"><div class="direct-chat-text-right">'.$r->message.'</div><div class="direct-chat-info clearfix"><span class="direct-chat-timestamp-right pull-right"><time class="timeago" id="timeago" datetime="'.$r->createdAt.'">'.$datesrt.'</time></span></div></div>';
				}
				else{
					$chat .=  ''.$datByday.'<div class="direct-chat-msg doted-border"><a href="./profile.php?userID='.$r->fromUserID.'"><img alt="message user image" src="'.$avatar.'" class="direct-chat-img"></a><div class="direct-chat-text">'.$r->message.'</div><div class="direct-chat-info clearfix"><span class="direct-chat-timestamp pull-left"><time class="timeago" id="timeago" datetime="'.$r->createdAt.'">'.$datesrt.'</time></span></div></div>';
				}
			}

			echo $chat;
		break;
	}
}
?>