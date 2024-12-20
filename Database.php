<?php
class Database
{
   public $connection;
   function __construct($config, $user = 'root', $password = '')
   {
      $dsn = 'mysql:' . http_build_query($config, '', ';');
      // dd($dsn);
      $this->connection = new PDO($dsn, $user, $password);
   }
   public function query($query, $params = [])
   {
      try {
         $statement = $this->connection->prepare($query);
         $statement->execute($params);
         return $statement;
      } catch (\Throwable $th) {
         echo $th;
      }
   }
}
