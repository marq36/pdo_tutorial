<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   include 'pportal_config.php';

   $uid = ($_POST['uid']);
   $pwd = ($_POST['pwd']);
   $hashedPwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
   
   //error handlers
   //check if input are empty
   if (empty($uid) || empty($pwd)) {
   	header("Location: /test/?login=empty");
   	exit();
   } else {
      //Create a template
   		$sql = 'SELECT * FROM users WHERE user_uid = :uid';
         //Create a prepared statement
         $stmt = $pdo->prepare($sql);
         //Prepare the prepared statment
         if (!$stmt = $pdo->prepare($sql)) {
            echo "SQL statement failed";
            exit();
         } else {
            //Bind parameters to the placeholders
            //mysqli_stmt_bind_param($stmt, "s",);
            //Run parameters inside database
            //mysqli_stmt_execute($stmt);
            $stmt->execute(['uid' => $uid]);
            $res = $stmt->rowCount();
            $resCheck = $stmt->rowCount($res);
            if ($resCheck < 1) {
               header("Location: /test/?login=error");
               exit();
            } else {
   			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   					//De-hashing the password
   				if (!password_verify($_POST['pwd'], $hashedPwd)){
                  header ("Location: /test/?login=error_pass_not_hashed");
                  echo 'Invalid password Marq, I thought you were going to become the worlds most improved developer?';
                  exit();
               } else {
   					//log in user here
   					//use super global $_SESSION
   					$_SESSION['u_id'] = $row['id'];
   					$_SESSION['u_company'] = $row['user_company'];
   					$_SESSION['u_phone'] = $row['user_phone'];
   					$_SESSION['u_email'] = $row['user_email'];
   					$_SESSION['u_uid'] = $row['user_uid'];
   					header ("Location: /test/portal.php?login=success");
   					exit();
   				}
   				}
               }
   		}
   }
} else {
	header("Location: /test/?login=error");
	exit();
}
