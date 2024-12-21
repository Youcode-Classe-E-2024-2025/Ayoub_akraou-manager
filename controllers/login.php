<?php
$errorMessage = NULL;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
   $username = $_POST["username"];
   $password = $_POST["password"];

   global $db;
   $query = "SELECT users.id, users.username, users.password, roles.role_name
            FROM users
            LEFT JOIN roles
            ON users.role_id = roles.id WHERE username = ?";
   $params = [$username];
   $user = $db->query($query, $params)->fetch(PDO::FETCH_ASSOC);
   if ($user) {
      if (password_verify($password, $user["password"])) {
         $_SESSION["id"] = $user["id"];
         $_SESSION["username"] = $user["username"];
         $_SESSION["admin"] = $user["role_name"] === "admin";
         if (isConnected()) {
            if (isAdmin()) header('Location: /dashboard');
            else header('Location: /');
            echo "connected";
         }
         echo "login succeed";
      } else {
         $errorMessage = "password incorrecte";
         echo "password incorrecte";
      }
   } else {
      $errorMessage = "user not found";
      echo "user not found";
   }
}

require "./views/login.view.php";
