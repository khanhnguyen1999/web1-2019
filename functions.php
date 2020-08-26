<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

function detectPage()
{
	$uri = $_SERVER['REQUEST_URI'];
	$parts = explode('/', $uri);
	$fileName = $parts[2];
	$parts = explode('.', $fileName);
	$page = $parts[0];
	return $page;
}

function findUserByEmail($email)
{
	global $db;
	$stmt = $db->prepare("Select * From teamx_user Where email = ?");
	$stmt->execute(array($email));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findUserById($userID)
{
	global $db;
	$stmt = $db->prepare("Select * From teamx_user Where userID = ?");
	$stmt->execute(array($userID));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findIdPostByTitle($title)
{
	global $db;
	$stmt = $db->prepare("SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID Where title= ?");
	$stmt->execute(array($title));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserPassword($userID, $password)
{
	global $db;
	$hashPassword = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $db->prepare("Update teamx_user Set password = ? Where userID = ?");
	return $stmt->execute(array($hashPassword, $userID));
}

function createUser($displayName, $email, $password, $phone, $ngaysinh)
{
	global $db, $BASE_URL;
	$hashPassword = password_hash($password, PASSWORD_DEFAULT);
	$code = generateRandomString(16);
	$stmt = $db->prepare("Insert Into teamx_user(displayName, email, password, phone, ngaysinh, chk_status, code) Values(?, ?, ?, ?, ?, ?, ?)");
	$stmt->execute(array($displayName, $email, $hashPassword, $phone, $ngaysinh, 0, $code));
	$id = $db->lastInsertId();

	sendEmail($email, $displayName, "Kích hoạt tài khoản", 
	"
	<!DOCTYPE html>
	<html lang='en'>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<meta http-equiv='X-UA-Compatible' content='ie=edge'>
		<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
		<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
	</head>
	<body>
		<div>
			<div style='text-align: center; width: 750px; border: 1px solid #ecf0f1; padding: 40px; border-radius: 10px; position: absolute; align-items: center; top: 25%; left: 25%;'>
				<img style='width: 72px; height: 72px;' src='cid:logo_email'>
				<h3>Chào mừng bạn $displayName đến với TeamX</h3>
				<p>Vui lòng xác nhận email để kích hoạt tài khoản của bạn.</p>
				<div style='width: 100%; max-width: 220px; padding: 10px; margin: 20px auto; border: 1px solid #ecf0f1; border-radius: 10px; font-size: 20px; background-color: #0097e6;''>
					<a style='color: whitesmoke; text-decoration: none; font-weight: bold;' href='$BASE_URL/activate.php?code=$code'>Xác nhận email</a>
				</div>
				<p style='font-size: smaller; opacity: 0.5;'>Nếu bạn không tham gia với TeamX, vui lòng bỏ qua email này. Xin cảm ơn.</p>
				<div style='padding: 15px; background-color: #bdc3c7; max-width: 100%; opacity: 0.3; font-size: 15px; border-radius: 5px;'>
					&copy; 2019 <b>TeamX</b>
				</div>
			</div>
		</div>    
	</body>
	</html>
	");

	return $id;
}

function updateUserInfo($userID, $displayName, $phone, $ngaysinh, $haveAvatar)
{
	global $db;
	$stmt = $db->prepare("Update teamx_user Set displayName = ?, phone = ?, ngaysinh = ?, haveAvatar = ? Where userID = ?");
	return $stmt->execute(array($displayName, $phone, $ngaysinh, $haveAvatar, $userID));
}

function resizeImage($filename, $max_width, $max_height)
{
  list($orig_width, $orig_height) = getimagesize($filename);

  $width = $orig_width;
  $height = $orig_height;

  # taller
  if ($height > $max_height) {
      $width = ($max_height / $height) * $width;
      $height = $max_height;
  }

  # wider
  if ($width > $max_width) {
      $height = ($max_width / $width) * $height;
      $width = $max_width;
  }

  $image_p = imagecreatetruecolor($width, $height);

  $image = imagecreatefromjpeg($filename);

  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

  return $image_p;
}

function getNewFeeds_index()
{
	global $db;
	$stmt = $db->query("SELECT p.*, u.displayName FROM teamx_user_status As p JOIN teamx_user AS u ON u.userID=p.userID WHERE privacy='Public' ORDER BY p.createdAt Desc");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createStatus($userID, $content, $privacy)
{
	global $db;
	$stmt = $db->prepare("Insert Into teamx_user_status(userID, content, privacy) Values(?, ?, ?)");
	$stmt->execute(array($userID, $content, $privacy));
	return $db->lastInsertId();
}

function convert_vi_to_en($str) {
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
	$str = str_replace(":", "", str_replace("&*#39;","",$str));
	$str = str_replace(",", "", str_replace("&*#39;","",$str));
	return $str;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendEmail($to, $name, $subject, $content) {
	global $EMAIL_FROM, $EMAIL_NAME, $EMAIL_PASS;
	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	//Server settings
	$mail->isSMTP();                                            // Send using SMTP
	$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	$mail->Username   = $EMAIL_FROM;                     // SMTP username
	$mail->Password   = $EMAIL_PASS;                               // SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
	$mail->Port       = 587;                                    // TCP port to connect to

	//Recipients
	$mail->setFrom($EMAIL_FROM, $EMAIL_NAME);
	$mail->addAddress($to, $name);     // Add a recipient
	$mail->AddEmbeddedImage('files/images/login/logo_email.png', 'logo_email', 'logo_email.png');
	// Content
	$mail->Subject = $subject;
	$mail->Body    = $content;
	$mail->isHTML(true);                                  // Set email format to HTML		
	//$mail->AltBody = $content;

	$mail->send();	
}

function SendNofifyByEmail($profile, $currentuser)
{
	global $BASE_URL;
	sendEmail($profile['email'], $profile['displayName'], "Lời mời kết bạn", 
	"
		<!DOCTYPE html>
		<html lang='en'>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<meta http-equiv='X-UA-Compatible' content='ie=edge'>
			<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
			<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
		</head>
		<body>
			<div>
				<div style='text-align: center; width: 750px; border: 1px solid #ecf0f1; padding: 40px; border-radius: 10px; position: absolute; align-items: center; top: 25%; left: 25%;'>
					<img style='width: 72px; height: 72px;' src='cid:logo_email'>
					<h3 style='color: #0097e6;'>Lời mời kết bạn từ TeamX</h3>
					<p>".$currentuser['displayName']." đã gửi mời lời kết đến bạn. Kích vào đây để đến trang cá nhân.</p>
					<div style='width: 100%; max-width: 220px; padding: 10px; margin: 20px auto; border: 1px solid #ecf0f1; border-radius: 10px; font-size: 20px; background-color: #0097e6;'>
						<a style='text-align: center; color: whitesmoke; text-decoration: none; font-weight: bold;' href='$BASE_URL/change-password-forgot.php?code=".$currentuser['userID']."'>
							".$currentuser['displayName']."
						</a>
					</div>
					<p style='font-size: smaller; opacity: 0.5;'>Nếu bạn không tham gia với TeamX, vui lòng bỏ qua email này. Xin cảm ơn.</p>
					<div style='text-align: center; padding: 15px; background-color: #bdc3c7; max-width: 100%; opacity: 0.3; font-size: 15px; border-radius: 5px;'>
						&copy; 2019 <b>TeamX</b>
					</div>
				</div>
			</div>    
		</html>
	"); 
}
//href='$BASE_URL/profile.php?userID=".$currentuser['userID']." -->

function activateUser($code)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user WHERE code = ? AND chk_status = ?");
	$stmt->execute(array($code, 0));
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($user && $user['code'] == $code){
		$stmt = $db->prepare("UPDATE teamx_user SET chk_status = ?, code = ? WHERE userID = ?");
		$stmt->execute(array( 1, '', $user['userID']));
		return true;
	}
	return false;
}

function forgotPass($userID)
{
	global $db, $BASE_URL;
	$code = generateRandomString(16);
	$stmt = $db->prepare("SELECT * FROM teamx_user WHERE userID = ?");
	$stmt->execute(array($userID));
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($user){
		$stmt = $db->prepare("UPDATE teamx_user SET code = ? WHERE userID = ?");
		$stmt->execute(array($code, $user['userID']));

		sendEmail($user['email'], $user['displayName'], "Đặt lại mật khẩu", 
		"
		<!DOCTYPE html>
		<html lang='en'>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<meta http-equiv='X-UA-Compatible' content='ie=edge'>
			<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
			<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
		</head>
		<body>
			<div>
				<div style='text-align: center; width: 750px; border: 1px solid #ecf0f1; padding: 40px; border-radius: 10px; position: absolute; align-items: center; top: 25%; left: 25%;'>
					<img style='width: 72px; height: 72px;' src='cid:logo_email'>
					<h3 style='color: #0097e6;'>Chào mừng ".$user['displayName']." quay lại với TeamX</h3>
					<p>Kích vào đây để đặt lại mật khẩu cho tài khoản của bạn.</p>
					<div style='width: 100%; max-width: 220px; padding: 10px; margin: 20px auto; border: 1px solid #ecf0f1; border-radius: 10px; font-size: 20px; background-color: #0097e6;''>
						<a style='text-align: center; color: whitesmoke; text-decoration: none; font-weight: bold;' href='$BASE_URL/change-password-forgot.php?code=$code'>Đặt lại mật khẩu</a>
					</div>
					<p style='font-size: smaller; opacity: 0.5;'>Nếu bạn không tham gia với TeamX, vui lòng bỏ qua email này. Xin cảm ơn.</p>
					<div style='text-align: center; padding: 15px; background-color: #bdc3c7; max-width: 100%; opacity: 0.3; font-size: 15px; border-radius: 5px;'>
						&copy; 2019 <b>TeamX</b>
					</div>
				</div>
			</div>    
		</body>
		</html>
		");
	}
	return false;
}

function activatePassChange($code, $password)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user WHERE code = ?");
	$stmt->execute(array($code));
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	$hashPassword = password_hash($password, PASSWORD_DEFAULT);
	if ($user && $user['code'] == $code){
		$stmt = $db->prepare("UPDATE teamx_user SET password = ?, code = ? WHERE userID = ?");
		$stmt->execute(array($hashPassword, '', $user['userID']));
		return true;
	}
	return false;
}

function getLastStatusIDofU($userID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_status WHERE userID = ? ORDER BY statusID DESC");
	$stmt->execute(array($userID));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function chkStatusIgm($statusID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_status_images WHERE statusID = ?");
	$stmt->execute(array($statusID));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function chkStatusisNull($statusID)
{
	global $db;
	$stmt = $db->prepare("SELECT content FROM teamx_user_status WHERE statusID = ?");
	$stmt->execute(array($statusID));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function sendFriendRequest($userID1, $userID2)
{
	global $db;
	$stmt = $db->prepare("INSERT INTO teamx_friendship(userID1, userID2) VALUES(?, ?)");
	$stmt->execute(array($userID1, $userID2));
	return $db->lastInsertId();
}

function removeFriendRequest($userID1, $userID2)
{
	global $db;
	$stmt = $db->prepare("DELETE FROM teamx_friendship WHERE (userID1 = ? AND userID2 = ?) OR (userID2 = ? AND userID1 = ?)");
	$stmt->execute(array($userID1, $userID2, $userID1, $userID2));
}

function getFriendShip($userID1, $userID2)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_friendship WHERE userID1 = ? AND userID2 = ?");
	$stmt->execute(array($userID1, $userID2));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function chkLikedStatus($statusID, $userID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_likes WHERE statusID = ? AND userID = ?");
	$stmt->execute(array($statusID, $userID));
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateLikeCountStatusAdd($statusID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_status WHERE statusID = ?");
	$stmt->execute(array($statusID));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$n = $row['likes'];

	$stmt = $db->prepare("UPDATE teamx_user_status SET likes=$n+1 WHERE statusID = ?");
	return $stmt->execute(array($statusID));
}

function updateLikeCountStatusRemove($statusID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_status WHERE statusID = ?");
	$stmt->execute(array($statusID));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$n = $row['likes'];

	$stmt = $db->prepare("UPDATE teamx_user_status SET likes=$n-1 WHERE statusID = ?");
	return $stmt->execute(array($statusID));
}

function likeStatus($statusID, $userID)
{
	global $db;
	$stmt = $db->prepare("INSERT INTO teamx_user_likes(statusID, userID) VALUES(?, ?)");
	$stmt->execute(array($statusID, $userID));
	return $db->lastInsertId();
}

function removeLikedStatus($statusID, $userID)
{
	global $db;
	$stmt = $db->prepare("DELETE FROM teamx_user_likes WHERE statusID = ? AND userID = ?");
	$stmt->execute(array($statusID, $userID));
}

function getNewNotiFy_Friend($n_type, $forUserID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_notify As n JOIN teamx_user AS u ON u.userID=n.fromUserID WHERE n_type = $n_type AND forUserID = $forUserID ORDER BY n.createdAt Desc");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sendNewNotiFy_Friend($n_type, $forUserID, $fromUserID)
{
	global $db;
	$stmt = $db->prepare("INSERT INTO teamx_notify(n_type, forUserID, fromUserID) VALUES(?, ?, ?)");
	$stmt->execute(array($n_type, $forUserID, $fromUserID));
	return $db->lastInsertId();
}

function delNewNotiFy_Friend($n_type, $forUserID, $fromUserID)
{
	global $db;
	$stmt = $db->prepare("DELETE FROM teamx_notify WHERE n_type = ? AND forUserID = ? AND fromUserID = ?");
	$stmt->execute(array($n_type, $forUserID, $fromUserID));
}

function sendNewNotiFy_NotiFy($n_type, $forUserID, $fromUserID)
{
	global $db;
	$stmt = $db->prepare("INSERT INTO teamx_notify(n_type, forUserID, fromUserID) VALUES(?, ?, ?)");
	$stmt->execute(array($n_type, $forUserID, $fromUserID));
	return $db->lastInsertId();
}

function getNewNotiFy_NotiFy($n_type, $forUserID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_notify As n JOIN teamx_user AS u ON u.userID=n.fromUserID WHERE n_type = $n_type AND forUserID = $forUserID ORDER BY n.createdAt Desc");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNewNotiFy_NotiFy_notisRead($n_type, $forUserID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_notify As n JOIN teamx_user AS u ON u.userID=n.fromUserID WHERE n_type = $n_type AND forUserID = $forUserID AND isRead = 0 ORDER BY n.createdAt Desc");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isReadNotify($id)
{
	global $db;
	$stmt = $db->prepare("Update teamx_notify Set isRead = ? Where id = ?");
	return $stmt->execute(array(1, $id));
}

function getUserStatus($userID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_status WHERE userID = $userID");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getImgUser($userID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user_status As u JOIN teamx_status_images AS i ON u.statusID=i.statusID WHERE userID = $userID");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFriendShipUserID($userID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_friendship WHERE userID1 = $userID");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeChat($fromUserID, $toUserID)
{
	global $db;
	$stmt = $db->prepare("DELETE FROM teamx_chat WHERE (fromUserID = ? AND toUserID = ?) OR (toUserID = ? AND fromUserID = ?)");
	$stmt->execute(array($fromUserID, $toUserID, $fromUserID, $toUserID));
}

function getNewNotiFy_Mess_notisRead($n_type, $forUserID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_notify As n JOIN teamx_user AS u ON u.userID=n.fromUserID WHERE n_type = $n_type AND forUserID = $forUserID AND isRead = 0 ORDER BY n.createdAt Desc");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNewNotiFy_Mess($n_type, $forUserID)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_notify As n JOIN teamx_user AS u ON u.userID=n.fromUserID WHERE n_type = $n_type AND forUserID = $forUserID ORDER BY n.createdAt Desc");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAll($id)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user where userID<>$id ORDER BY RAND ( ) LIMIT 6");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAlls($id)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function yourFriends($id)
{
	global $db;
	$stmt = $db->prepare("SELECT * FROM teamx_user where userID<>$id ORDER BY RAND ( ) LIMIT 4");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
