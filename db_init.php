<?php
require "./Database.php";
$dbname = 'blogs_manager';
$config = require "./config.php";
$db = new Database($config);

try {
   // Connexion au serveur MySQL
   global $db;
   $db->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Vérifier si la base de données existe, sinon la créer
   $db->query("CREATE DATABASE IF NOT EXISTS $dbname");
   $db->query("USE $dbname");

   // Création de la table Users
   $db->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT NOT NULL,
        -- status ENUM('active', 'archived') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    )");

   // Création de la table Roles
   $db->query("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

   // Création de la table Blogs
   $db->query("CREATE TABLE IF NOT EXISTS blogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        status ENUM('published', 'draft') DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        published_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

   // Création de la table Logs
   $db->query("CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        action VARCHAR(255) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

   // Création de la table Settings
   $db->query("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        `key` VARCHAR(255) NOT NULL,
        `value` VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
    )");

   // -------------------------------------------------------------------------------
   // Insérer des rôles 
   if (!tableExist("roles")) {
      $roles = ['admin', 'user'];
      foreach ($roles as $role) {
         $query = "INSERT INTO roles (role_name) VALUES ($role)";
         $db->query($query);
      }
      echo "<br>roles a été crée</br>";
   }

   // Insérer des utilisateurs 

   if (!tableExist("users")) {
      $users = [
         ['username' => 'admin_user', 'email' => 'admin@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'role_id' => 1],
         ['username' => 'john_doe', 'email' => 'john@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'role_id' => 2],
         ['username' => 'jane_doe', 'email' => 'jane@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'role_id' => 2],
         ['username' => 'isaak_dev', 'email' => 'isaak@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'role_id' => 2],
      ];
      foreach ($users as $user) {
         $query = "INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)";
         $db->query($query, $user);
      }
      echo "<br>users a été crée</br>";
   }

   // Insérer des blogs 
   if (!tableExist("blogs")) {
      $blogs = [
         ['user_id' => 2, 'title' => 'First Blog', 'content' => 'This is the first blog post.', 'status' => 'published', 'published_at' => date('Y-m-d H:i:s')],
         ['user_id' => 2, 'title' => 'Second Blog', 'content' => 'This is the second blog post.', 'status' => 'draft'],
         ['user_id' => 3, 'title' => 'Third Blog', 'content' => 'Another blog post.', 'status' => 'published', 'published_at' => date('Y-m-d H:i:s')],
      ];
      $query = "INSERT INTO blogs (user_id, title, content, status, published_at) VALUES (:user_id, :title, :content, :status, :published_at)";
      foreach ($blogs as $blog) {
         $db->query($query, [
            'user_id' => $blog['user_id'],
            'title' => $blog['title'],
            'content' => $blog['content'],
            'status' => $blog['status'],
            'published_at' => $blog['published_at'] ?? null
         ]);
      }
      echo "<br>blogs a été crée</br>";
   }

   // Insérer des logs 
   if (!tableExist("logs")) {
      $logs = [
         ['user_id' => 1, 'action' => 'login'],
         ['user_id' => 2, 'action' => 'create_account'],
         ['user_id' => 1, 'action' => 'approve_user'],
      ];
      $query = "INSERT INTO Logs (user_id, action) VALUES (:user_id, :action)";
      foreach ($logs as $log) {
         $db->query($query, $log);
      }
      echo "<br>logs a été crée</br>";
   }

   // Insérer des paramètres  pour les utilisateurs
   if (!tableExist("settings")) {
      $settings = [
         ['user_id' => 1, 'key' => 'theme', 'value' => 'dark'],
         ['user_id' => 2, 'key' => 'theme', 'value' => 'light'],
         ['user_id' => 3, 'key' => 'language', 'value' => 'en'],
      ];
      $query = "INSERT INTO settings (user_id, `key`, `value`) VALUES (:user_id, :key, :value)";
      foreach ($settings as $setting) {
         $db->query($query, $setting);
      }
      echo "<br>settings a été crée</br>";
   }
} catch (PDOException $e) {
   die("Erreur : " . $e->getMessage());
}
