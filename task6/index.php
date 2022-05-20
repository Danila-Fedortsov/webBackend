<?php
header('Content-Type: text/html; charset=UTF-8');

$db_user = 'u41067';
$db_pass = '34636774'
$db = new PDO('mysql:host=localhost;dbname=u41067', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
));

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    $messages['save'] = '';
    $messages['notsave'] = '';
    $messages['name'] = '';
    $messages['email'] = '';
    $messages['powers'] = '';
    $messages['bio'] = '';
    $messages['check'] = '';

    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        $messages['save'] = 'Спасибо, результаты отправлены на сервер.';
        if (!empty($_COOKIE['pass'])) {
            $messages['savelogin'] = sprintf(' Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass']));
        }
    }

    if (!empty($_COOKIE['notsave'])) {
        setcookie('notsave', '', 100000);
        $messages['notsave'] = strip_tags($_COOKIE['notsave']);
    }

    $errors = array();
    $errors['name'] = empty($_COOKIE['name_error']) ? '' : strip_tags($_COOKIE['name_error']);
    $errors['email'] = empty($_COOKIE['email_error']) ? '' : strip_tags($_COOKIE['email_error']);
    $errors['powers'] = empty($_COOKIE['powers_error']) ? '' : strip_tags($_COOKIE['powers_error']);
    $errors['bio'] = empty($_COOKIE['bio_error']) ? '' : strip_tags($_COOKIE['bio_error']);
    $errors['check'] = empty($_COOKIE['check_error']) ? '' : strip_tags($_COOKIE['check_error']);

    if ($errors['name'] == 'null') {
        setcookie('name_error', '', 100000);
        $messages['name'] = 'Заполните имя.';
    }
    else if ($errors['name'] == 'incorrect') {
        setcookie('name_error', '', 100000);
        $messages['name'] = 'Недопустимые символы. Введите имя заново.';
    }

    if ($errors['email']=='null') {
        setcookie('email_error', '', 100000);
        $messages['email'] = 'Заполните почту.';
    }  
    else if ($errors['email'] == 'incorrect') {
        setcookie('email_error', '', 100000);
        $messages['email'] = 'Недопустимые символы. Введите e-mail заново.';
    }

    if ($errors['powers']) {
        setcookie('powers_error', '', 100000);
        $messages['powers'] = 'Выберите хотя бы одну сверхспособность.';
    }

    if ($errors['bio']) {
        setcookie('bio_error', '', 100000);
        $messages['bio'] = 'Напишите что-нибудь о себе.';
    }

    if ($errors['check']) {
        setcookie('check_error', '', 100000);
        $messages['check'] = 'Вы не можете отправить форму не согласившись с контрактом!';
    }

    $powers = array();

    $powers['tp'] = "Телепортация";
    $powers['levit'] = "Левитация";
    $powers['vision'] = "Ночное зрение";

    $values = array();

    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
    $values['year'] = empty($_COOKIE['year_value']) ? '' : strip_tags($_COOKIE['year_value']);
    $values['gender'] = empty($_COOKIE['gender_value']) ? 'male' : strip_tags($_COOKIE['gender_value']);
    $values['count'] = empty($_COOKIE['count_value']) ? '4' : strip_tags($_COOKIE['count_value']);
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
    $powers_value = empty($_COOKIE['powers_value']) ? '' : json_decode($_COOKIE['powers_value']);

    $values['powers'] = [];

    if (isset($powers_value) && is_array($powers_value)) {
        foreach ($powers_value as $power) {
            if (!empty($powers[$power])) {
                $values['powers'][$power] = $power;
            }
        }
    }

    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {

        $messages['save'] = ' ';
        $messages['savelogin'] = 'Вход с логином '.$_SESSION['login'];

        try {
            $stmt = $db->prepare("SELECT * FROM web6 WHERE login = ?");
            $stmt->execute(array(
                $_SESSION['login']
            ));
            $user_data = $stmt->fetch();
            $values['name'] = strip_tags($user_data['name']);
            $values['email'] = strip_tags($user_data['email']);
            $values['year'] = strip_tags($user_data['year']);
            $values['gender'] = strip_tags($user_data['gender']);
            $values['count'] = strip_tags($user_data['count']);
            $values['bio'] = strip_tags($user_data['bio']);
            $powers_value = explode(", ", $user_data['powers']);
            $values['powers'] = [];
            foreach ($powers_value as $power) {
                if (!empty($powers[$power])) {
                    $values['powers'][$power] = $power;
                }
            }

        } catch(PDOException $e) {
            setcookie('notsave', 'Ошибка: ' . $e->getMessage());
            exit();
        }
    }

    include('form.php');
}
else {
    $errors = FALSE;
    if (empty($_POST['name'])) {
        setcookie('name_error', 'null', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else if (!preg_match("#^[aA-zZ0-9-]+$#", $_POST["name"])) {
        setcookie('name_error', 'incorrect', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else if (!preg_match("#\w+@\w+\.\w+#", $_POST["email"])) {
        setcookie('email_error', 'incorrect', time() + 24 * 60 * 60);
        $errors = TRUE;
    }

    else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }

    $powers = array();

    foreach ($_POST['powers'] as $key => $value) {
        $powers[$key] = $value;
    }

    if (!sizeof($powers)) {
        setcookie('powers_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('powers_value', json_encode($powers), time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['bio'])) {
        setcookie('bio_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    else {
        setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['check'])) {
        setcookie('check_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }

    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    setcookie('count_value', $_POST['count'], time() + 30 * 24 * 60 * 60);

    if ($errors) {
        header('Location: index.php');
        exit();
    }
    else {
        setcookie('name_error', '', 100000);
        setcookie('name_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('powers_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('check_error', '', 100000);
    }

    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {

        try {
            $stmt = $db->prepare("UPDATE web6 SET name = ?, email = ?, year = ?, gender = ?, count = ?, powers = ?, bio = ? WHERE login = ?");
            $stmt->execute(array(
                $_POST['name'],
                $_POST['email'],
                $_POST['year'],
                $_POST['gender'],
                $_POST['count'],
                implode(', ', $_POST['powers']),
                $_POST['bio'],
                $_SESSION['login']
            ));
        } catch(PDOException $e) {
            setcookie('notsave', 'Ошибка: ' . $e->getMessage());
            exit();
        }

    }
    else {
        $login = uniqid("id");
        $pass = rand(100000, 999999);
        // Сохраняем в Cookies.
        setcookie('login', $login);
        setcookie('pass', $pass);

        try {
            $stmt_form = $db->prepare("INSERT INTO web6 SET login = ?, pass = ?, name = ?, email = ?, year = ?, gender = ?, count = ?, powers = ?, bio = ?");
            $stmt_form->execute(array(
                $login,
                hash('sha256', $pass, false),
                $_POST['name'],
                $_POST['email'],
                $_POST['year'],
                $_POST['gender'],
                $_POST['count'],
                implode(', ', $_POST['powers']),
                $_POST['bio']
            ));
        } catch(PDOException $e) {
            setcookie('notsave', 'Ошибка: ' . $e->getMessage());
            exit();
        }
    }

    setcookie('save', '1');

    header('Location: ./');
}