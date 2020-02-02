<?php
    require_once 'base.php';
    $base = new Base();
    
    $newCheck = false;
    if(isset($_GET['dataYear'])){ 
        $date = $_GET['dataYear'].'-'.$_GET['dataMonth'].'-'.$_GET['dataDay'];
        $newCheck = true;
    }
    if(isset($_GET['contr'])) $contr = $_GET['contr'];

    if(isset($_GET['checkSum'])) $checkSum = $_GET['checkSum'];
    
    if(isset($_GET['typeOper'])) $typeOper = $_GET['typeOper'];

    $i = 0;
    $prodList = [];
    while($i < 30){
        if(isset($_GET['prod'.$i]) and ($_GET['prod'.$i] != '')) $prodList[] = $_GET['prod'.$i];
        $i++;
    }

    $i = 0;
    $priceList = [];
    while($i < 30){
        if(isset($_GET['price'.$i]) and ($_GET['price'.$i] != '')) $priceList[] = $_GET['price'.$i];
        $i++;
    }

    $i = 0;
    $commentList = [];
    while($i < 30){
        if(isset($_GET['comment'.$i]) and ($_GET['comment'.$i] != '')) $commentList[] = $_GET['comment'.$i];
        $i++;
    }

    
    if($newCheck){
        //Запишем контрагента и дату чека
        $query = "INSERT INTO `checks` VALUES ( default, '$date', '$contr', '$typeOper', '$checkSum')";
        print_r($query);
        $base->query($query,false);
        $query = "SELECT id from `checks`";
        $idList = $base->query($query,true);
        //print_r($idList);
        $lastId = $idList[count($idList)-1]['id'];

        //запишем список продуктов
        $queryString = "";
        forEach($prodList as $key=>$value){
            $currPrise = $priceList[$key];
            $currProd = $value;
            if(isset($commentList[$key])) {
                $currComment = $commentList[$key];
            }else{
                $currComment = '';
            }
            $queryString .= "( default, '$lastId', '$currPrise', '$currProd', '$currComment'),";
        }
        $queryString = substr($queryString,0,strlen($queryString)-1);
        //print_r($queryString);
        $query = "INSERT INTO `moves` VALUES $queryString";
        print_r($query);
        $base->query($query,false);
        header('Location: moves.php');
    }


    $query = "SELECT `id`, `name` FROM `contragents`";
    $contrList = $base->query($query, true);

    $query = "SELECT `id`, `name` FROM `items` WHERE `category` IS NOT NULL ORDER BY `name`";
    $itemList = $base->query($query, true);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Система учета финансов v_0.1</title>
  <meta charset="utf8">
</head>

<body>
  <a href="index.php">Назад</a>
  <form action="moves.php" method="get">
    <h3>Операции прихода - расхода</h3>
    Дата:
    <select name="dataDay" class="dataDay">
      <?php
                    
                    for($i = 1; $i <= 31; $i++){
                        print_r('<option value="'.$i.'">'.$i.'</option>');
                    }
                ?>
    </select>
    <select name="dataMonth" class="dataMonth">
      <option value="01">Январь</option>
      <option value="02">Февраль</option>
      <option value="03">Март</option>
      <option value="04">Апрель</option>
      <option value="05">Май</option>
      <option value="06">Июнь</option>
      <option value="07">Июль</option>
      <option value="08">Август</option>
      <option value="09">Сентябрь</option>
      <option value="10">Октябрь</option>
      <option value="11">Ноябрь</option>
      <option value="12">Декабрь</option>
    </select>
    <select name="dataYear" class="dataYear">
      <option value="2019">2019</option>
      <option value="2020">2020</option>
    </select>

    <br />
    Контрагент:
    <select name="contr">
      <?php
                    forEach($contrList as $key=>$value){
                        print_r('<option value="'.$value['id'].'">'.$value['name'].'</option>');
                    }
                ?>
    </select>
    <br />
    <select name="typeOper">
      <option value="1">Заработали</option>
      <option value="2">Покупка</option>
      <option value="3">Вернули долг</option>
      <option value="4">Взяли в долг</option>
      <option value="5">Приняли в подарок</option>
      <option value="6">Подарили</option>
      <option value="7">Десятина</option>
      <option value="8">Дали в долг</option>
      <option value="9">Ввод остатков</option>
    </select>
    <span id="checkSum"></span><input type="submit">
    <input hidden name="checkSum" class="checkSum">
    <br />

    Список товаров-услуг:
    <table>
      <thead>
        <tr>
          <th>Товар</th>
          <th>Стоимость</th>
        </tr>
      </thead>
      <tbody>
        <?php
                        $i=0;
                        while($i < 30){
                            print_r('<tr><td><select name="prod'.$i.'"><option></option>');
                            forEach($itemList as $key=>$value){
                                print_r('<option value="'.$value['id'].'">'.$value['name'].'</option>');
                            }
                            print_r('</select></td><td><input type="number" name="price'.$i.'" class="price" onblur="getSumOfCheck();"></td><td><input type="text" name="comment'.$i.'"></td></tr>');
                            $i++;
                        }
                    ?>
      </tbody>
    </table>
  </form>
  <script>
    function setCurrDate() {
      let now = new Date();
      let nowYear = now.getFullYear();
      let nowMonth = now.getMonth() + 1;
      let nowDay = now.getDate();


    }

    //setCurrDate();

    function getSumOfCheck() {
      let sum = document.querySelectorAll('.price');
      let res = 0;
      for (let i = 0; i < sum.length; i++) {
        res += Number(sum[i].value);
      }
      document.querySelector('#checkSum').textContent = res / 100;
      document.querySelector('.checkSum').value = res;
    }

  </script>
</body>

</html>
