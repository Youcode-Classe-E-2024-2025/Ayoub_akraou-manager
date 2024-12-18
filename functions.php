<?php
function URL_is($url)
{
   $path = parse_url($_SERVER["REQUEST_URI"])['path'];
   return $path === $url;
}

function dd($value)
{
   echo '<pre class="bg-white">';
   var_dump($value);
   echo '</pre>';
   die();
}

function tableExist($table)
{
   global $config;
   global $db;
   $stmt = $db->query("SELECT COUNT(*) FROM $table");
   $tableExists = $stmt->fetchColumn() > 0;
   return $tableExists;
}
