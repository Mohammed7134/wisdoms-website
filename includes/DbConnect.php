<?php
class DbConnect
{
    private $conn;
    function __construct()
    {
    }

    function connect()
    {
        require_once 'Constants.php';
        $this->conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if (!$this->conn) {
            die("Failed to connect to MySQL: "); //. mysqli_conncet_error());
        }
        if (!$this->conn->set_charset("utf8")) {
            printf("Error loading character set utf8mb4: %s\n", $this->conn->error);
            exit();
        }
        // else {
        // printf("Current character set: %s\n", $this->conn->character_set_name());
        // }

        return $this->conn;
    }
}
