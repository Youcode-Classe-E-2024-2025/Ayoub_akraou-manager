<?php
if (isConnected()) header('Location: /');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   // ajouter ce nouveau utilisateur a base de données:
   global $db;
   $username = $_POST["username"];
   $email = $_POST["email"];
   $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

   $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
   $params = [$username, $email, $password];
   $db->query($query, $params);

   $id = $db->connection->lastInsertId();
   $_SESSION["id"] = $id;
   $_SESSION["username"] = $username;
   // prener le a la page des blogs
   // header("Location: /blogs");
};

require "./views/signup.view.php";
