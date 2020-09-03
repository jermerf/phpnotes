<?php

class UserModel
{

  public $id;
  public $username;
  private $password;
  public $lastLogin;
  public $admin = false;

  function __construct($username, $password)
  {
    $this->username = $username;
    $this->password = $password;
  }

  public function register()
  {
    global $con;

    $query = <<<QUERY
    INSERT INTO session_user (username, password) VALUE (:uname , :pword)
    QUERY;

    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    $stm = $con->prepare($query);

    $stm->bindParam(":uname", $this->username);
    $stm->bindParam(":pword", $hashedPassword);

    if ($stm->execute()) {
      $this->id = $con->lastInsertId();
      $this->lastLogin = time();
      $this->loginSuccess();
    } else {
      throw new UsernameTakenException($this->username);
    }
  }

  public function login()
  {
    global $con;

    $query = <<<QUERY
    SELECT id, username, password, last_login, admin 
    FROM session_user 
    WHERE username = :uname
    QUERY;

    $stm = $con->prepare($query);

    $stm->bindParam(":uname", $this->username);

    if ($stm->execute()) {
      if ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($this->password, $row['password'])) {
          $this->id = $row['id'];
          $this->admin = ($row['admin'] ? true : false);
          $this->lastLogin = time();
          $this->loginSuccess();
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  function loginSuccess()
  {
    global $con;

    // Update the last_login
    $query = "UPDATE session_user SET last_login = :lastLogin WHERE id = :uid";

    $stm = $con->prepare($query);
    $stm->bindParam(':uid', $this->id);
    $stm->bindParam(':lastLogin', $this->lastLogin);

    $stm->execute();

    // Remember that we're logged in - WITH SESSIONS!!!
    session_start();
    $_SESSION['userId'] = $this->id;
    $_SESSION['username'] = $this->username;
    $_SESSION['admin'] = $this->admin;
  }
}

class UsernameTakenException extends Exception
{
  public $username;
  function __construct($username)
  {
    $this->username = $username;
  }

  function __toString()
  {
    return "'$this->username' is taken";
  }
}
