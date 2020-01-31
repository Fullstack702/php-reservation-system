<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */


if (!empty($_POST)) {
	require "./config.php";
	if (isset($_POST['time'])) {
		$connection = new PDO($dsn, $username, $password, $options);
		$sql = "UPDATE users SET contact_time=? WHERE email=?";
		$stmt = $connection->prepare($sql);
		$stmt->execute([$_POST['time'], $_COOKIE['email']]);
		$res = ['status' => 1];
		header('Content-type: application/json');
		echo json_encode($res);
	} else {
		try {
			$connection = new PDO($dsn, $username, $password, $options);

			$sql = "CREATE TABLE IF NOT EXISTS users (
                id INT NOT NULL AUTO_INCREMENT, 
                PRIMARY KEY(id),
                lastname VARCHAR(30) NOT NULL,
                firstname VARCHAR(30) NOT NULL,
                email VARCHAR(50) NOT NULL,
                phone INT(3),
                address VARCHAR(50) NOT NULL,
                zip INT(5),
                city VARCHAR(50) NOT NULL,
                referer VARCHAR(255) NOT NULL,
                ip VARCHAR(255) NOT NULL,
                os VARCHAR(50) NOT NULL,
                browser VARCHAR(50) NOT NULL,
                device VARCHAR(50) NOT NULL,
                contact_time VARCHAR(50) NOT NULL,
                house_type INT(1),
                date TIMESTAMP
            );";
			$statement = $connection->prepare($sql);
			$statement->execute();

			$sql = "SELECT * FROM users WHERE email='" . $_POST['email'] . "'";
			$result = $connection->prepare($sql);
			$result->execute();
			$result = $connection->prepare("SELECT FOUND_ROWS()");
			$result->execute();
			$row_count = $result->fetchColumn();
			if ($row_count > 0) {
				$res = ['msg' => 'Votre inscription a déjà été prise en compte', 'status' => 0];
			} else {
				$new_user = array(
					"lastname" => $_POST['lastname'],
					"firstname" => $_POST['firstname'],
					"email" => $_POST['email'],
					"phone" => $_POST['phone'],
					"address" => $_POST['address'],
					"zip" => $_POST['zip'],
					"city" => $_POST['city'],
					"house_type" => $_POST['house_type'],
					"referer" => $_POST['referrer'],
					"ip" => getUserIpAddr(),
					"os" => getOS(),
					"browser" => getBrowser(),
					"device" => $_POST['device'],
				);

				setcookie('email', $_POST['email']);

				$sql = sprintf(
					"INSERT INTO %s (%s) values (%s)",
					"users",
					implode(", ", array_keys($new_user)),
					":" . implode(", :", array_keys($new_user))
				);

				$statement = $connection->prepare($sql);
				$statement->execute($new_user);
				$res = ['status' => 1];
			}
			header('Content-type: application/json');
			echo json_encode($res);
		} catch (PDOException $error) {
			$res = ['msg' => $error->getMessage(), 'status' => 0];
			header('Content-type: application/json');
			echo json_encode($res);
		}
	}
}

function getUserIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//ip pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function getOS()
{

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$os_platform  = "Unknown OS Platform";

	$os_array     = array(
		'/windows nt 10/i'      =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);

	foreach ($os_array as $regex => $value)
		if (preg_match($regex, $user_agent))
			$os_platform = $value;

	return $os_platform;
}

function getBrowser()
{

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$browser        = "Unknown Browser";

	$browser_array = array(
		'/msie/i'      => 'Internet Explorer',
		'/firefox/i'   => 'Firefox',
		'/safari/i'    => 'Safari',
		'/chrome/i'    => 'Chrome',
		'/edge/i'      => 'Edge',
		'/opera/i'     => 'Opera',
		'/netscape/i'  => 'Netscape',
		'/maxthon/i'   => 'Maxthon',
		'/konqueror/i' => 'Konqueror',
		'/mobile/i'    => 'Handheld Browser'
	);

	foreach ($browser_array as $regex => $value)
		if (preg_match($regex, $user_agent))
			$browser = $value;

	return $browser;
}
?>