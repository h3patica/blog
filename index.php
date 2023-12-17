<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css.css">
<title>hepatiki</title>
</head>

<?php
date_default_timezone_set('EST');
echo "<a href='/'><div class=term style='display:flex;justify-content:center'><pre>
   __                   __   _  __    _ 
  / /  ___  ___  ___ _ / /_ (_)/ /__ (_)
 / _ \/ -_)/ _ \/ _ `// __// //  '_// / 
/_//_/\__// .__/\_,_/ \__//_//_/\_\/_/
         /_/
A small personal blog / wiki</pre></div></a>";

$db = new PDO('sqlite:posts.db');
$query = $_SERVER['QUERY_STRING'];

$res = select_with_query($query, $db);
foreach ($res as $res) {
    $tags = explode(" ", $res['tags']);
    $date = date("d M Y H:i", $res['date']);
    $parsed_text = parse_text($res['content']);
    echo "<div class=term style='margin-top:5px'>
    <div style='float:right'>{$res['date']}</div>
    <div style='float:left'>tags: ";
    foreach ($tags as $tag) {
        echo "<a href='/?{$tag}'>{$tag}</a> ";
    }
    echo "</div><br>{$date}<br>{$parsed_text}</div>";
}

function select_with_query($query, $db)
{
    if (strlen($query) > 0){
        $stmt = $db->prepare('SELECT * FROM tbl1 WHERE tags LIKE :query ORDER BY date DESC');
        $stmt->bindValue(':query', "%$query%");
    } else {
        $stmt = $db->prepare('SELECT * FROM tbl1 ORDER BY date DESC');
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function parse_text($content)
{
    $textarr = explode("\n", $content);
    for ($i = 0; $i < sizeof($textarr); $i++) {
        $str = $textarr[$i];
        if (strcmp($str[0], ">") == 0) {
            $rest = substr($str, 1);
            $textarr[$i] = "<span class='greentext'>&gt;{$rest}</span>";
        } else if (strcmp($str[0], "<") == 0) {
            $rest = substr($str, 1);
            $textarr[$i] = "<span class='redtext'>&lt;{$rest}</span>";
        }
    }
    return join("<br>", $textarr);
}
