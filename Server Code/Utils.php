<?php
 
class Utils {
 
    private $mysqli;
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "hombot";
 
    function __construct() {
        $this->dbConnect();
    }

    /*
    Database connection
     */
    private function dbConnect(){
        $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
        /* check connection */
        if ($this->mysqli->connect_errno) {
            die($this->mysqli->connect_error);
        }
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        #$stmt = $this->mysqli->prepare("INSERT INTO users(name, email, encrypted_password, salt) VALUES(?, ?, ?, ?)");
        #$stmt->bind_param('ssss', $name, $email, $encrypted_password, $salt);
        #$stmt->execute(); 
        #$result = $stmt->get_result();
        //$stmt->close();
        $query = "Insert into users(name, email, encrypted_password, salt) values('$name', '$email', '$encrypted_password', '$salt')";
        $result = $this->mysqli->query($query);
        if ($result) {
            return self::getUserByEmailAndPassword($email, $password);
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
        $query = "Select * from users where email = '$email'";
        $res = $this->mysqli->query($query);
        if($res->num_rows) {
            $result = mysqli_fetch_array($res);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $query = "Select * from users WHERE email = '$email'";
        $result = $this->mysqli->query($query);
        if($result->num_rows) {
            return true;
        } else {
            // user not existed
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = [
                    "salt" => $salt, 
                    "encrypted" => $encrypted
                ];
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 
} 
?>