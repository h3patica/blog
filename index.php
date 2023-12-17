<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css.css">
</head>

<?php
echo "<a href='/'><div class=term style='display:flex;justify-content:center'><pre>
   __                   __   _  __    _ 
  / /  ___  ___  ___ _ / /_ (_)/ /__ (_)
 / _ \/ -_)/ _ \/ _ `// __// //  '_// / 
/_//_/\__// .__/\_,_/ \__//_//_/\_\/_/
         /_/
A small personal blog / wiki</pre></div></a>";

$db = new PDO('sqlite:posts.db');
$query = $_SERVER['QUERY_STRING'];

if (strlen($query) > 0){
    $stmt = $db->prepare('SELECT * FROM tbl1 WHERE tags LIKE :query');
    $stmt->bindValue(':query', "%$query%");
    $stmt->execute();
    $res = $stmt->fetchAll();
    foreach ($res as $res) {
        $tags = explode(" ", $res['tags']);
        $date = date("d M Y", $res['date']);
        echo "<div class=term style='margin-top:5px'>
        <div style='float:right'>{$res['date']}</div>
        <div style='float:left'>tags: ";
        foreach ($tags as $tag) {
            echo "<a href='/?{$tag}'>{$tag}</a> ";
        }
        echo "</div><br>{$date}<br>{$res['content']}</div>";
    }
} else {
    $stmt = $db->prepare('SELECT * FROM tbl1');
    $stmt->execute();
    $res = $stmt->fetchAll();
    foreach ($res as $res) {
        $tags = explode(" ", $res['tags']);
        $date = date("d M Y", $res['date']);
        echo "<div class=term style='margin-top:5px'>
        <div style='float:right'>{$res['date']}</div>
        <div style='float:left'>tags: ";
        foreach ($tags as $tag) {
            echo "<a href='/?{$tag}'>{$tag}</a> ";
        }
        echo "</div><br>{$date}<br>{$res['content']}</div>";
    }
}
