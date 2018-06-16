<?php
//check to see if button was clicked
//partners/signup.php
if (isset($_POST['signup'])) {

  include_once 'pportal_config.php';

  //guard against sql injection with mysqli_real_escape_string
  $company = ($_POST['company']);
  $phone = ($_POST['phone']);
  $email = ($_POST['email']);
  $uid = ($_POST['uid']);
  $pwd = ($_POST['pwd']);

  //error handlers
  //check for empty fields
  //pass check
  if (empty($company) || empty($phone) || empty($email) || empty($uid) || empty($pwd)) {
    header("Location: /test/?signup=empty");
    exit();
  } else {
    //check if input characters are valid
    //pass check
    if (!preg_match("/^[a-zA-Z]*$/", $uid)) {
      header("Location: /test/?signup=invalid&email=$email&phone=$phone&company=$company");
      exit();
    } else {
      //check for valid email
      //pass check
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /test/?signup=emailinvalid&company=$company&phone=$phone&uid=$uid");
        exit();
        //run query
        //pass check
      } else {
        //Create a template
        $sql = 'SELECT * FROM users WHERE user_email = :email';
        //Create a prepared statement
        $stmt = $pdo->prepare($sql);
        //Prepare the statement
        if (!$stmt = $pdo->prepare($sql)) {
          echo "SQL statement failed.";
          exit();
        } else {
          //Bind parameters to placeholders
          //run parameters inside database
          $stmt->execute(['email' => $email]);
        $res = $stmt->rowCount();
        $resCheck = $stmt->rowCount($res);
        //check for duplicate entry
        //pass check
        if ($resCheck > 0) {
          header("Location: /test/?signup=usertaken");
          exit();
        } else {
          //hash password
          $hashedPwd = password_hash('pwd', PASSWORD_DEFAULT);
          $stmt = $pdo->prepare($hashedPwd);
          //insert the user into the database
          //Prepare the statement
          $sql = "INSERT INTO users (user_company, user_phone, user_email, user_uid, user_pwd) VALUES (:company, :phone, :email, :uid, :pwd)";
          $stmt = $pdo->prepare($sql);
          //execute the statement and insert data
          $stmt->execute(['company' => $company, 'phone' => $phone, 'email' => $email, 'uid' => $uid, 'pwd' => $hashedPwd]);
          echo "Sign Up Success.";
          header("Location: /test/?signup=SUCCESS");
          exit();
        } 
      }
    }
  }
}
} else {
    header("location: /test/?signup=SOMETHING_WENT_WRONG");
    exit();
}