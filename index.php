<form action="/" method="POST">
    <label> Логин
        <input name="login" type="text">
    </label> <br>
    <label> Пароль
        <input name="password" type="text">
    </label> <br>
    <label> Сообщение
        <input name="message" type="text">
    </label> <br>
    <input type="submit" name="button" value="Войти и отправить">
    <input type="submit" name="resetButton" value="Очистить историю">
</form>

<?php
    $main_fileJSON = "userMessages.json";
    $users_fileJSON = "users.json";
    $message = $_POST['message'];
    $Date = date("H:i:s");
    $login = $_POST['login'];
    $password = $_POST['password'];

    function main_writeJSON($fileJSON, $login, $Date, $message)
    {
        $userMessages = json_decode(file_get_contents($fileJSON), true);
        $userMessages['messages'][] = ['user' => $login, 'date' => $Date, 'message' => $message];
        file_put_contents($fileJSON, json_encode($userMessages));
    }

    function users_writeJSON($fileJSON, $login, $password)
    {
        $usersArray = json_decode(file_get_contents($fileJSON), true);
        $usersArray['users'][] = ['login' => $login, 'password' => $password];
        file_put_contents($fileJSON, json_encode($usersArray));
    }

    function printMessage($fileJSON)
    {
        $jsonObject = json_decode(file_get_contents($fileJSON), false);

        foreach ($jsonObject->messages as $user)
        {
            $Login_Date = $user->user . ' ' . $user->date;
            echo "<div style='text-align: center'>$Login_Date</div>";
            echo "$user->message<br/><br/>";
        }
    }

    function checkUser($fileJSON, $login, $password) : bool
    {
        $jsonObject = json_decode(file_get_contents($fileJSON), false);

        foreach ($jsonObject->users as $user)
        {
            if ($login === $user->login && $password !== $user->password)
            {
                echo "<script> alert(\"Неверный пароль\") </script>";
                return false;
            }
            elseif ($login === $user->login && $password === $user->password)
                return true;
        }
        users_writeJSON($fileJSON, $login, $password);
        return true;
    }

    if (isset($_POST['resetButton']))
    {
        $jsonObject = json_decode(file_get_contents($main_fileJSON), false);
        foreach ($jsonObject->messages as $user)
        {
            unset($jsonObject->messages);
        }
        file_put_contents($main_fileJSON, json_encode($jsonObject));
        unset($jsonObject);

        echo "<script> document.getElementById('Message_Block').innerHTML = \"\"; </script>";
    }

    if (isset($_POST['button']))
    {
        if ($login === '' || $password === '') {
            echo "<script>alert(\"Для авторизации введите логин и пароль!\")</script>";
        } else if ($message === '')
            echo "<script>alert(\"Поле для сообщения не должно быть пустым!\")</script>";
        else if(checkUser($users_fileJSON, $login, $password))
        {
            main_writeJSON($main_fileJSON, $login, $Date, $message);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }
?>

<html lang="eng">
    <body>
        <div id="Message_Block">
            <?php printMessage($main_fileJSON); ?>
        </div><br>
    </body>
</html>

<style>
    #Message_Block {
        border: 1px solid gray;
        width: 400px;
        height: 600px;
        margin: 0 auto;
        text-align: right;
        overflow-y: scroll;
        overflow-x:hidden;
    }
</style>