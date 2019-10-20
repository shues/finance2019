<?php
    require_once 'base.php';
    $base = new Base('localhost','root','glos2ar12','finance2019');


    if(isset($_GET['newItem'])){
        $newItem = $_GET['newItem'];
        $query = "INSERT INTO `items` VALUES ( default, '$newItem', NULL )";
        $base->query($query, false);
        header('Location: items.php');
    }
        
    $query = "SELECT `id`, `name` FROM `items` ORDER BY `name`";
    $contrList = $base->query($query, true);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Система учета финансов v_0.1</title>
    </head>
    <body>
       <a href="index.php">Назад</a>
        <h3>Список товаров и услуг</h3>
        <ul>
        <?php
            forEach($contrList as $key=>$value){
                print_r('<li>'.$value['name'].'</li>');
            }
        ?>
        </ul>
        <form action="items.php" method="get">
            <input type="text" name="newItem" autofocus>
            
            <input type="submit">
        </form>
    </body>
</html>