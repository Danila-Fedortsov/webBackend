<?php
header('Content-Type: text/html; charset=UTF-8');

session_start();

$db_user = 'u41067';   // Логин БД
$db_pass = '34636774';  // Пароль БД

if (!empty($_SESSION['login'])) {
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" placeholder="Ваш логин" />
  <input name="pass" placeholder="Ваш пароль" />
  <input type="submit" value="Войти" />
</form>

<?php
}
else {.
  $login = $_POST['login'];
  $pass =  hash('sha256', $_POST['pass'], false);

  $db = new PDO('mysql:host=localhost;dbname=u41035', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
  ));

  try {
    $stmt = $db->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute(array(
      $login
    ));.
    $user = $stmt->fetch();

    if ($pass == $user['pass']) {
      $_SESSION['login'] = $login;
    }
    else {
      echo "Неправильный логин или пароль";
      exit();
    }

  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }

  header('Location: ./');
}