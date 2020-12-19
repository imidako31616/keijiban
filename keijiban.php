<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>簡易掲示板</title>
</head>

<body>
<h1>簡易掲示板</h1>
<h2>好きな食べ物について</h2>
<p>〇好きな食べ物を教えてください〇</p>

<form action = "keijiban.php" method = "post">
    <input type = "text"   name = "name" placeholder="名前">
    <input type = "text" name = "comment" placeholder="コメント">
    <input type = "hidden" name ="editNo" >
    <input type = "submit" value = "送信"><br/>
</from>
<from action = "keijiban.php" method ="post">    
    <input type="text" name="deleteNo" placeholder="削除したい番号">
    <input type="text" name="password_del" placeholder="パスワード" >
    <input type="submit" name="delete" value="削除"><br/>
</from>
<from action = "keijiban.php" method ="post">
    <input type="text" name="editt" placeholder="編集対象番号">
    <input type="text" name="password_edit" placeholder="パスワード">
    <input type="submit" name="編集" value="編集"><br/>
    
    
</from>

<?php
$dsn = 'mysql:dbname=*******;host=*******';
$user = '********';
$password = '*********';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


$today = date("Y-m-d H:i:s");


$sql = "CREATE TABLE IF NOT EXISTS keijiban"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        .$today
        .");";
    $stmt = $pdo->query($sql);
    
//書き込み

    //名前とコメントが空じゃなかったら
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editt"])){
    
    //新規書き込み
    $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $sql -> execute();
    
    }

    //編集対象番号があったら
    
    if(empty($_POST["editt"]) && empty($_POST["password_edit"])){
       echo"";  
       
    }else{
    //編集作業
    if($_POST["password_edit"] != "morning"){
         echo"編集するパスワードが違います<br/>";
    
    }else{
    $id = $_POST["editt"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $sql = 'UPDATE keijiban SET name=:name,comment=:comment WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();

    }
    }
    
        
    //削除
    $id = $_POST["deleteNo"];
    $sql = 'delete from keijiban where id=:id';
    
    if(empty($_POST["deleteNo"]) && empty($_POST["password_del"])){
        
        echo"";
        
    }else{
        if($_POST["password_del"] != "hello"){
        echo "削除するパスワードが違います<br/>";
        }else{
            
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        echo"";

            
        }

    }
    
       
    //表示
    $sql = 'SELECT * FROM keijiban';
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchALL();
    foreach($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'<br>';
    echo "<hr>";
    }   
?>


</body>
</html>