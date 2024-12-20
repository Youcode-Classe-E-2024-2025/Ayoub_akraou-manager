<?php
require "./Database.php";
$dbname = 'blogs_manager';
$config = require "./config.php";
$query = file_get_contents("db.sql");
// dd($query);
$db = new Database([
   'host' => 'localhost',
   // 'port' => '3306',
]);


try {
   // Connexion au serveur MySQL
   global $db;
   $db->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // -------------------------------------------------------------------------------
   if (!isDatabaseExist($dbname)) {
      $db->query($query);
      $db = new Database($config['database']);
      // Insérer des rôles 
      $roles = ['admin', 'user'];
      foreach ($roles as $role) {
         $query = "INSERT INTO roles (role_name) VALUES ('$role')";
         $db->query($query);
      }

      // Insérer des utilisateurs 
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

      // Insérer des blogs 
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
      // Insérer des logs 
      $logs = [
         ['user_id' => 1, 'action' => 'login'],
         ['user_id' => 2, 'action' => 'create_account'],
         ['user_id' => 1, 'action' => 'approve_user'],
      ];
      $query = "INSERT INTO Logs (user_id, action) VALUES (:user_id, :action)";
      foreach ($logs as $log) {
         $db->query($query, $log);
      }

      // Insérer des paramètres  pour les utilisateurs
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
