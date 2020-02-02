<?php
require "config.php";
$time = $_POST['time'];

$sql = "SELECT *
FROM users
where contact_time like 'instant_call'
and date > '" . $time . "' ORDER BY date ";
$connection = new PDO($dsn, $username, $password, $options);
$statement = $connection->prepare($sql);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$return = '';
foreach ($result as $row) {
    $houseType = $row[" house_type"] == 1 ? 'Une maison' : 'Un appartement';
    $return .= "<tr>";
    $return .= "<td style='color:red;'>" . $row["date"] . "</td>";
    $return .= "<td>" . $row["contact_time"] . "</td>";
    $return .= "<td>" . $houseType . "</td>";
    $return .= "<td>" . $row["lastname"] . "</td>";
    $return .= "<td>" . $row["firstname"] . "</td>";
    $return .= "<td>" . $row["address"] . "</td>";
    $return .= "<td>" . $row["zip"] . "</td>";
    $return .= "<td>" . $row["city"] . "</td>";
    $return .= "<td>" . $row["email"] . "</td>";
    $return .= "<td>" . $row["phone"] . "</td>";
    $return .= "<td>" . $row["referer"] . "</td>";
    $return .= "<td>" . $row["ip"] . "</td>";
    $return .= "<td>" . $row["os"] . "</td>";
    $return .= "<td>" . $row["device"] . "</td>";
    $return .= "<td><img class='btn-remove' src='./images/delete.png' width='32' data-toggle='modal' data-target='#modal' onclick='remove(" . $row['id'] . ");'></td>";
    $return .= "</tr>";
}
header('Content-type: application/json');
echo json_encode($return);
exit;
