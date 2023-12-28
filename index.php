<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css.css">
<link rel="icon" href="wikipetan.png">
<title>hepatiki</title>
</head>

<?php
date_default_timezone_set('EST');
echo "<div class=term id='header'>
<div id='header'><pre>
   __                   __   _  __    _
  / /  ___  ___  ___ _ / /_ (_)/ /__ (_)
 / _ \/ -_)/ _ \/ _ `// __// //  '_// /
/_//_/\__// .__/\_,_/ \__//_//_/\_\/_/
         /_/
A small personal blog / wiki
</pre></div>
<div style='position:absolute;right:5px'>
<a href='/'>Index</a>
 |
<a href='rss.xml'>RSS</a>
 |
<a>Webring</a>
</div>
</div>";

$db = new PDO('sqlite:posts.db');
if (array_key_exists('QUERY_STRING', $_SERVER)) {
    $query = explode("=", $_SERVER['QUERY_STRING']);
} else {
    $query = array(0 => NULL);
}

$res = select_with_query($query, $db);
foreach ($res as $res) {
    $tags = explode(" ", $res['tags']);
    $timestamp = $res['date'];
    $date = date("d M Y H:i", $timestamp);
    $parsed_text = parse_text($res['content']);
    echo "<div class=term>
    <a style='float:right' href='/?date=$timestamp'>{$timestamp}</a>
    <div style='float:left'>tags: ";
    foreach ($tags as $tag) {
        echo "<a href='/?tags={$tag}'>{$tag}</a> ";
    }
    echo "</div><br>{$date}<br>{$parsed_text}</div>";
}

function select_with_query($query, $db)
// $query[0] = "date" or "tags"
// $query[1] = the id/tag to be searched for
{
    if (strlen($query[0]) > 0){
        $name = $query[0];
        $search = $query[1];
        switch ($name) {
            case "date":
            $stmt = $db->prepare('SELECT * FROM tbl1 WHERE date LIKE ? ORDER BY date DESC');
            break;
            case "tags":
            $stmt = $db->prepare('SELECT * FROM tbl1 WHERE tags LIKE ? ORDER BY date DESC');
            break;
        }
        $stmt->execute(["%$search%"]);
    } else {
        $stmt = $db->prepare('SELECT * FROM tbl1 ORDER BY date DESC');
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

function parse_text($content)
{
    $textarr = explode("\n", $content);
    for ($i = 0; $i < sizeof($textarr); $i++) {
        $str = $textarr[$i];
        $firstchar = substr($str, 0, 1);
        if (strcmp($firstchar, ">") == 0) {
            $rest = substr($str, 1);
            $textarr[$i] = "<span class='greentext'>&gt;{$rest}</span>";
        } else if (strcmp($firstchar, "<") == 0) {
            $rest = substr($str, 1);
            $textarr[$i] = "<span class='redtext'>&lt;{$rest}</span>";
        }
    }
    return join("<br>", $textarr);
}
