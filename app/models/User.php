<?php

class User
{

    function __construct()
    { }

    function checkErrors()
    {

        $errors = array();

        $pass = $_POST['pass'];
        $confirm_pass = $_POST['confirm-pass'];

        if ($pass !== $confirm_pass) {
            array_push($errors, "Las contraseña no coinciden.");
        }

        $sql = "SELECT count(*) as num_usuarios ";
        $sql .= "FROM users ";
        $sql .= "WHERE trim(lower(nickname)) = '" . trim(strtolower($_POST['nickname'])) . "'";

        $db = new MySQLDB();

        $data = $db->getDataSingle($sql);

        $num_users = $data['num_usuarios'];

        if ($num_users > 0) {
            array_push($errors, "El nickname ya existe.");
        }

        $sql = "SELECT count(*) as num_usuarios ";
        $sql .= "FROM users ";
        $sql .= "WHERE trim(lower(email)) = '" . trim(strtolower($_POST['email'])) . "'";

        $data = $db->getDataSingle($sql);

        $num_users = $data['num_usuarios'];

        if ($num_users > 0) {
            array_push($errors, "El email ya existe.");
        }

        $db->close();

        return $errors;
    }

    function registry()
    {

        $sql = "INSERT INTO users VALUES(";
        $sql .= "null,";
        $sql .= "'" . $_POST['username'] . "', ";
        $sql .= "'" . $_POST['surname'] . "', ";
        $sql .= "'" . $_POST['nickname'] . "', ";
        $sql .= "'" . $_POST['email'] . "', ";
        $sql .= "'" . hash_hmac("sha512", $_POST['pass'], "discoduroderoer") . "', ";
        $sql .= "'" . date("Y-m-d") . "', ";
        if(empty($_POST['avatar'])){
            $sql .= "'" . $_POST['avatar'] . "', ";
        }else{
            $sql .= "null, ";
        }
        $sql .= "2, ";
        $sql .= " '" . (new DateTime())->format('Y-m-d H:i') . "' , ";
        $sql .= "0, ";
        $sql .= "0);";

        $db = new MySQLDB();

        $success = $db->insertData($sql);

        $db->close();

        return $success;

    }
}