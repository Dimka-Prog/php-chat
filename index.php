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
</form>

<?php
    $fileJSON = "userMessages.json";
    $message = $_POST['message'];
    $Date = date("H:i:s");
    $login = $_POST['login'];
    $password = $_POST['password'];

    function writeJSON($fileJSON, $login, $Date, $message)
    {
        $userMessages = json_decode(file_get_contents($fileJSON), true);
        $userMessages['messages'][] = ['user' => $login, 'date' => $Date, 'message' => $message];
        file_put_contents($fileJSON, json_encode($userMessages));
    }

    function printMessage($fileJSON)
    {
        $jsonArray = json_decode(file_get_contents($fileJSON), false);

        foreach ($jsonArray->messages as $user)
        {
            $Login_Date = $user->user . ' ' . $user->date;
            echo "<div style='text-align: center'>$Login_Date</div>";
            echo "$user->message<br/><br/>";
        }
    }

    if (isset($_POST['button']))
    {
        if ($login === '' || $password === '')
        {
            echo "<script>alert(\"Для авторизации введите логин и пароль!\")</script>";
        }
        else if($message === '')
            echo "<script>alert(\"Поле для сообщения не должно быть пустым!\")</script>";
        else {
                writeJSON($fileJSON, $login, $Date, $message);
        }
    }
?>

<html lang="eng">
    <body>
        <div id="Message_Block">
            <?php printMessage($fileJSON);?>
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
    }
</style>