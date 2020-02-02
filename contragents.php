<?php
    require_once 'base.php';
    $base = new Base();


    if(isset($_GET['newContr'])){
        $newContr = $_GET['newContr'];
        $query = "INSERT INTO `contragents` VALUES ( default, '$newContr', null )";
        $base->query($query, false);
        header('Location: contragents.php');
    }
        
    $query = "SELECT `id`, `name` FROM `contragents` ORDER BY `name`";
    $contrList = $base->query($query, true);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Система учета финансов v_0.1</title>
        <meta charset="utf8">
    </head>
    <body>
       <a href="index.php">Назад</a>
        <h3>Список контрагентов</h3>
        <ul>
        <?php
            forEach($contrList as $key=>$value){
                print_r('<li>'.$value['name'].'</li>');
            }
        ?>
        </ul>
        <form action="contragents.php" method="get">
            <input type="text" name="newContr">
            
            <input type="submit">
        </form>
    </body>
</html>