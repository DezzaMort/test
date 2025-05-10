<?php

require_once('../config/config.php');
require_once('../src/db.php');

function getJson(string $url): array
{
    $json = file_get_contents($url);

    return  json_decode($json, true);
}

function insertPost(PDO $db, array $data): bool
{
    $values = [
      'id' => $data['id'],
      'user_id' => $data['userId'],
      'title' => $data['title'],
      'body' => $data['body'],
    ];

    $sql = 'INSERT INTO posts (id, user_id, title, body)
            VALUES (:id, :user_id, :title, :body)';

    return execute($db, $sql, $values);
}

function insertComment(PDO $db, array $data): bool
{

    $values = [
        'id'      => $data['id'],
        'post_id' => $data['postId'],
        'name'    => $data['name'],
        'email'   => $data['email'],
        'body'    => $data['body'],
    ];


    $sql = 'INSERT INTO comments (id, post_id, name, email, body) 
            VALUES (:id, :post_id, :name, :email, :body)';


    return execute($db, $sql, $values);
}

$db = connectToDb();

$posts = getJson(POSTS_URL);
$counterPosts = 0;

foreach ($posts as $post) {
    if (insertPost($db, $post)) {
        $counterPosts++;
    }
}


$comments = getJson(COMMENTS_URL);
$counterComments = 0;

foreach ($comments as $comment) {
    if (insertComment($db, $comment)) {
        $counterComments++;
    }
}

echo "Загружено $counterPosts постов и $counterComments комментариев";