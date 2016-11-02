<?php

// display all records
$app -> get('/api/books',function(){
    require_once ('dbconnect.php');
    $query = "select * from books order by id";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        if(isset($data)){
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
    else echo '<h1>База сдохла!</h1>';
});

// display a single row

$app -> get('/api/books/{id}',function($request){

    require_once ('dbconnect.php');
    $id = $request->getAttribute('id');
    echo 'The ID is '.$id;
    $query = "select * from books where id=$id";
    $result = $mysqli->query($query);
    $data = $result->fetch_assoc();
    if ($data!=null){
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    else echo '<h1>Строки такой нету ! 404 !</h1>';
});

// post data and create a new record
$app -> post('/api/books',function ($request){

    require_once ('dbconnect.php');
    $query = "INSERT INTO `books` ( `book_title`, `author`, `amazom_url`) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt -> bind_param("sss",$a ,$b, $c); // http://php.net/manual/en/mysqli-stmt.bind-param.php

//    $a = "Язык программирования Go";
//    $b = "Алан А. А. Донован, Брайан У. Керниган";
//    $c = "http://www.ozon.ru/context/detail/id/34671680/";

    $a = $request->getParsedBody()['book_title'];
    $b = $request->getParsedBody()['author'];
    $c = $request->getParsedBody()['amazom_url'];

    $stmt -> execute();
    echo 'Done!';

//    $myname = $request->getParsedBody()['myname'];
//    echo 'Hello again '.$myname;
});

// update a record on the database
$app -> put('/api/books/{id}',function ($request){

    $id = $request->getAttribute('id');
    require_once ('dbconnect.php');
    $query = "UPDATE `books` SET `book_title` = ?, `author` = ?, `amazom_url` = ? WHERE `books`.`id` = $id";
    $stmt = $mysqli->prepare($query);
    $stmt -> bind_param("sss",$a ,$b, $c);
    $a = $request->getParsedBody()['book_title'];
    $b = $request->getParsedBody()['author'];
    $c = $request->getParsedBody()['amazom_url'];
    $stmt -> execute();
    echo 'Done!';
//    require_once ('dbconnect.php');
//    $query = "UPDATE `books` SET `book_title` = ?, `author` = ?, `amazom_url` = ? WHERE `books`.`id` = ?";
});

// delite a record on the database
$app -> delete('/api/books/{id}',function ($request){

    $id = $request->getAttribute('id');
    require_once ('dbconnect.php');
    $query = "DELETE from `books` WHERE `books`.`id` = $id";
    $result = $mysqli->query($query);
    if ($result) echo 'Запись удалена!';
});