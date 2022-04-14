<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Задание №3</title>
</head>

<body>
    <h1>Форма</h1>
    <form action="form.php" method="post">
        <div class="box">
            <p>
                <h3>Имя</h3>
                <label>
				    <input placeholder="Имя" type="text" name="name" value="">
			    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>E-mail</h3>
                <label>
				<input placeholder="E-mail" type="text" name="email" value="">
		    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>Год рождения</h3>
                <label>
				    <select name="year">
				    	<option value="">Выбрать</option>
				    	<?php
				    	for ($i = 2008; $i >= 1900; --$i) {
				    		echo "<option value='$i'>$i</option>";
				    	}
					    ?>
				    </select>
			    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>Пол</h3>
                <label>
				    <input type="radio" name="gender" value="man">Мужской
			    </label>
                <label>
				    <input type="radio" name="gender" value="woman">Женский
			    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>Количество конечностей</h3>
                <label>
				    <input type="radio" name="numlimbs" value="1">1
			    </label>
                <label>
				    <input type="radio" name="numlimbs" value="2">2
			    </label>
                <label>
				    <input type="radio" name="numlimbs" value="3">3
		    	</label>
                <label>
				    <input type="radio" name="numlimbs" value="4">4
		    	</label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>Сверхспособности</h3>
                <label>
				    <select multiple name="super[]">
					    <option value="im">Бессмертие</option>
					    <option value="ww">Прохождение сквозь стены</option>
					    <option value="le">Левитация</option>
				    </select>
			    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <h3>Биография</h3>
                <label>
			        <textarea placeholder="Расскажите о себе" name="biography"></textarea>
			    </label>
            </p>
        </div>
        <div class="box">
            <p>
                <label>
				    <input type="checkbox" name="agree">С контранктом ознакомлен(-а)
			    </label>
            </p>
            <p>
                <input type="submit" value="Отправить">
            </p>
        </div>
    </form>

</body>

</html>