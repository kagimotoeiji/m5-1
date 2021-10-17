<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
            //接続
            $dsn = "mysql:dbname=データベース名;host=localhost";
            $user = "ユーザー名";
            $password = "パスワード";
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            //作成
            $sql = "CREATE TABLE IF NOT EXISTS tb51"
            ."("
            ."id INT AUTO_INCREMENT PRIMARY KEY,"
            ."name char(32),"
            ."comment TEXT,"
            ."pass char(32),"
            ."date DATETIME"
            .");";
            $stmt = $pdo->query($sql);
            
            
            //投稿機能
            //名前とコメントフォームが空でないとき
            if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
                // 編集番号表示とパスワードフォームが空のとき新規投稿
                if(empty($_POST["edit_num"]) && empty($_POST["edit_pass"])){
                    
                    //新規投稿
                    $sql = $pdo -> prepare("INSERT INTO tb51 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
                    $sql -> bindParam(":name", $name, PDO::PARAM_STR);
                    $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
                    $sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
                    $sql -> bindParam(":date", $date, PDO::PARAM_STR);
                    //送信された値を変数に
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $pass = $_POST["pass"];
                    //日付設定
                    $date = date("Y-m-d H:i:s");
                    $sql -> execute();
                    
                //編集機能
                }else{
                    $edit_id = $_POST["edit_num"];
                    $edit_name = $_POST["name"];
                    $edit_comment = $_POST["comment"];
                    $edit_pass = $_POST["pass"];
                    $date = date("Y-m-d H:i:s");
                    $sql = "SELECT * FROM tb51";
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($row["id"] == $edit_id && $row["pass"] == $edit_pass){
                            $sql = "UPDATE tb51 SET name=:name,comment=:comment,date=:date WHERE id=:id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(":name", $edit_name, PDO::PARAM_STR);
                            $stmt->bindParam(":comment", $edit_comment, PDO::PARAM_STR);
                            $stmt->bindParam(":date",$date,PDO::PARAM_STR);
                            $stmt->bindParam(":id", $edit_id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
            }
            
            //削除機能
            if(!empty($_POST["delete"]) && !empty($_POST["delete_pass"])){
                $delete_id = $_POST["delete"];
                $delete_pass = $_POST["delete_pass"];
                $sql = "SELECT * FROM tb51";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if($row["id"] == $delete_id && $row["pass"] == $delete_pass){
                        $sql = "delete from tb51 where id=:id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":id", $delete_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
            
            //編集選択機能
            //編集番号表示フォームが空でないとき
            if(!empty($_POST["edit"]) && !empty($_POST["edit_pass"])){
                $edit_id = $_POST["edit"];
                $edit_pass = $_POST["edit_pass"];
                $sql = "SELECT * FROM tb51";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if($edit_id == $row["id"] && $edit_pass == $row["pass"]){
                        $edit_num_form = $row["id"];
                        $edit_name_form = $row["name"];
                        $edit_comment_form = $row["comment"];
                    }
                }
            }
        ?>
        
        <form action = "" method = "post">
            <input type = "text" name = "name" placeholder="名前" value = "<?php if(isset($edit_name_form)) {echo $edit_name_form;} ?>"><br>
            <input type = "text" name = "comment" placeholder="コメント" value = "<?php if(isset($edit_comment_form)) {echo $edit_comment_form;} ?>"><br>
            <input type = "hidden" name = "edit_num" value = "<?php if(isset($edit_num_form)) {echo $edit_num_form;} ?>">
            <input type = "text" name = "pass" placeholder = "パスワード"><br>
            <input type = "submit" value = "送信"><br>
            
            <input type = "number" name = "delete" placeholder = "削除対象番号"><br>
            <input type = "text" name = "delete_pass" placeholder = "パスワード"><br>
            <input type = "submit" value = "削除"><br>
            
            <input type = "number" name = "edit" placeholder = "編集対象番号"><br>
            <input type = "text" name = "edit_pass" placeholder = "パスワード"><br>
            <input type = "submit" value = "編集"><br>
        </form>
        
        <?php
            //表示機能
            $sql = "SELECT * FROM tb51";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row["id"].",";
                echo $row["name"].",";
                echo $row["comment"].",";
                echo $row["date"]."<br>";
                echo "<hr>";
            }
        ?>
    </body>
</html>