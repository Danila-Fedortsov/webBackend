<?php

header('Content-Type: text/html; charset=UTF-8');
if (!empty($_POST)) {
	if (empty($_POST["name"])) {
		$errors[] = "*Обязательно";
	}
	if (empty($_POST["email"])) {
		$errors[] = "*Обязательно";
	}
	if (empty($_POST["year"])) {
		$errors[] = "*Обязательно";
	}
	if (!isset($_POST["gender"])) {
		$errors[] = "*Обязательно";
	}
	if (!isset($_POST["numlimbs"])) {
		$errors[] = "*Обязательно";
	}
	if (!isset($_POST["super"])) {
		$errors[] = "*Обязательно";
	}
	if (empty($_POST["biography"])) {
		$errors[] = "*Обязательно";
	}
} else {
	$errors[] = "Неверные данные формы!";
}

if (isset($errors)) {
	foreach ($errors as $value) {
		echo "$value<br>";
	}
	exit();
}
$name = htmlspecialchars($_POST["name"]);
$email = htmlspecialchars($_POST["email"]);
$year = intval(htmlspecialchars($_POST["year"]));
$gender = htmlspecialchars($_POST["gender"]);
$limbs = intval(htmlspecialchars($_POST["numlimbs"]));
$superPowers = $_POST["super"];
$biography = htmlspecialchars($_POST["biography"]);
if (!isset($_POST["agree"])) {
	$agree = 0;
} else {
	$agree = 1;
}

$serverName = 'localhost';
$user = "u41067";
$pass = "34636774";
$dbName = $user;

$db = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $pass, array(PDO::ATTR_PERSISTENT => true));

$lastId = null;
try {
	$stmt = $db->prepare("INSERT INTO user (name, email, date, gender, limbs, biography, agreement) VALUES (:name, :email, :date, :gender, :limbs, :biography, :agreement)");
	$stmt->execute(array('name' => $name, 'email' => $email, 'date' => $year, 'gender' => $gender, 'limbs' => $limbs, 'biography' => $biography, 'agreement' => $agree));
	$lastId = $db->lastInsertId();
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}

try {
	if ($lastId === null) {
		exit();
	}
	foreach ($superPowers as $value) {
		$stmt = $db->prepare("INSERT INTO user_power (id, power) VALUES (:id, :power)");
		$stmt->execute(array('id' => $lastId, 'power' => $value));
	}
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}
$db = null;
echo "Успешно!";