<?php

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('admin')) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

$db_user = 'u41067';
$db_pass = '34636774';

$db = new PDO('mysql:host=localhost;dbname=u41067', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
));
try {
    $stmt_form = $db->prepare("INSERT INTO admin SET login = ?, pass = ?");
    $stmt_form->execute(array(
        $_SERVER['PHP_AUTH_USER'],
        hash('sha256', $_SERVER['PHP_AUTH_PW'], false)
    ));
}catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $db->prepare('DELETE FROM web6 WHERE login = ?');
        $stmt->execute(array(
            $_POST['remove']
        ));
    }
    catch (PDOException $e) {
        echo 'Ошибка: ' . $e->getMessage();
        exit();
    }
}

try {
    $stmt = $db->query('SELECT * FROM web6');
    ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Админ панель | Задание 6</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <form action="" method="post">
        <?php
        $stmt2 = $db->query(
        'SELECT SUM((length(w.powers) - length(replace(w.powers, "tp", "")))/2),
        SUM((length(w.powers) - length(replace(w.powers, "vision", "")))/6),
        SUM((length(w.powers) - length(replace(w.powers, "levit", "")))/5)
        FROM web6 w'
        );
        ?>
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth">
                <thead>
                <tr>
                    <th>Логин</th>
                    <th>Пароль</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Год гождения</th>
                    <th>Пол</th>
                    <th>Количество конечностей</th>
                    <th>Сверхспособности</th>
                    <th>Биография</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                <?php

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    print('<tr>');
                    foreach ($row as $cell) {
                        print('<td>' . $cell . '</td>');
                    }

                    print('<td><button class="button is-info is-small is-danger is-light" name="remove" type="submit" value="' . $row['login'] . '">x</button></td>');
                    print('</tr>');
                   }

                ?>
                </tbody>
               </table>
        </div>
        <table class="table is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Телепортация</th>
                <th>Ночное зрение</th>
                <th>Левитация</th>
            </tr>
            </thead>
            <tbody>
            <h3>Статистика способностей</h3>
            <?php
            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                print('<tr>');
                foreach ($row as $cell) {
                    print('<td>' . $cell . '</td>');
                }
                print('</tr>');
            }
            ?>
            </tbody>
        </table>
    </form>
    </body>
    <?php
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
}