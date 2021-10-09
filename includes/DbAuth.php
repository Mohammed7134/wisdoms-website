<?php
class DbAuth
{
    private $conn;

    function __construct()
    {
        require_once dirname(__FILE__) . '/Constants.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    public function __destruct()
    {
        $this->conn->close();
    }

    public function userLogin($username, $password)
    {
        $usernameExists = $this->isUserExist($username);
        if ($usernameExists === OBJECT_NOT_EXIST) {
            return OBJECT_NOT_EXIST;
        } else if (isset($usernameExists['id'])) {
            $storedPassword = $usernameExists['password'];
            $passwordVerify = password_verify($password, $storedPassword);
            if ($passwordVerify === false) {
                return INVALID_CREDENTIALS;
            } else if ($passwordVerify === true) {
                $user = array();
                $user['id'] = $usernameExists['id'];
                $user['username'] = $usernameExists['username'];
                $user['admin'] = $usernameExists['admin'];
                return $user;
            } else {
                return "Unexpected Error";
            }
        } else if ($usernameExists === OBJECT_NOT_CREATED) {
            return "Error logging in";
        } else {
            return INVALID_CREDENTIALS;
        }
    }

    public function createUser($username, $pass)
    {
        if ($this->isUserExist($username) == OBJECT_NOT_CREATED) {
            return OBJECT_NOT_CREATED;
        } else if ($this->isUserExist($username) == OBJECT_NOT_EXIST) {
            $sql = "INSERT INTO users (username, password) VALUES (?, ?);";
            $stmt = mysqli_stmt_init($this->conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                return OBJECT_NOT_CREATED;
            }
            $password = password_hash($pass, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return OBJECT_CREATED;
        } else {
            return OBJECT_ALREADY_EXIST;
        }
    }

    private function isUserExist($username)
    {
        $sql = "SELECT id, username, password, admin FROM users WHERE username = ?;";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return OBJECT_NOT_CREATED;
        }
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $stmt->bind_result($id, $uname, $password, $admin);
            $user = array();
            while ($stmt->fetch()) {
                $user['username'] = $uname;
                $user['id'] = $id;
                $user['password'] = $password;
                $user['admin'] = $admin;
            }
            return $user;
        } else {
            return OBJECT_NOT_EXIST;
        }
        $stmt->close();
    }
}
