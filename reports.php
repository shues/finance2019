<?php
    require_once 'base.php';

    $base = new Base('localhost','root','glos2ar12','finance2019');
    
    $rtype = isset($_GET['rtype'])?$_GET['rtype']:null;
    $period = isset($_GET['period'])?$_GET['period']:null;
    
    
    
    $monthNumber = isset($_GET['rmonth'])?$_GET['rmonth']:date('n');

    switch($period){
        case 'day':
            $whereString = "WHERE date = CURDATE()-15";
            break;
        case 'month':
            $whereString = "WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
            break;
        default:
            $whereString = "";
            break;
    }
    // Запрос для получения списка чеков с контрагентами и операциями за дату
    $query = "select checks.id as num, contragents.name as contr, types.name as oper, sum(sum) as sum from checks left join contragents on contragents.id = checks.contragent left join types on types.id=checks.type left join moves on moves.checkn=checks.id where month(date) = 10 AND dayofmonth(date)=15 group by num, contr, oper";
    

$query = "
        SELECT 
            date, 
            name, 
            sum 
        FROM 
            contragents 
        RIGHT JOIN 
            (
                SELECT 
                    contragent, 
                    date, 
                    IF (type=2, 0-sum, sum) AS sum 
                FROM 
                    checks 
                LEFT JOIN 
                    (
                        SELECT
                            checkn, 
                            sum(sum) as sum 
                        FROM
                            moves
                        GROUP BY
                            checkn
                    ) AS buf 
                ON 
                    checks.id = buf.checkn 
                $whereString
            ) AS bufchecks 
        ON 
            contragents.id = bufchecks.contragent 
        ORDER BY
            date
    ";

    $query = "
        select 
            name, 
            sum 
        from 
            items 
        right join 
            (
                select 
                    category, 
                    sum(sum) as sum 
                from 
                    items 
                right join 
                    (
                        select 
                            itemn, 
                            sum(sum) as sum 
                        from 
                            moves 
                        group by 
                            itemn
                    ) as buf 
                on 
                    items.id = buf.itemn 
                group by 
                    category
            ) as buf1 
        on 
            id = buf1.category
    ";
    $categoryQuery = "
        select 
            name,
            type,
            sum 
        from 
            (
                select 
                    category,
                    type,
                    sum(sum) as sum 
                from 
                    checks 
                left join 
                    moves 
                on 
                    checks.id=moves.checkn 
                left join 
                    items 
                on 
                    items.id=itemn 
                where (
                    type = 2
                or
                    type = 3
                or
                    type = 6
                or
                    type = 7
                )
                and
                    month(date) = $monthNumber 
                group by 
                    type, 
                    category
            ) as buf 
        left join 
            items 
        on 
            items.id=buf.category
        ORDER BY 
            sum DESC
            
    ";
    $categoryRes = $base->query($categoryQuery,true);
    
    $productQuery = "select 
            name,
            type,
            sum 
        from 
            (
                select 
                    category,
                    type,
                    sum(sum) as sum 
                from 
                    checks 
                left join 
                    moves 
                on 
                    checks.id=moves.checkn 
                left join 
                    items 
                on 
                    items.id=itemn 
                where (
                    type = 2
                or
                    type = 3
                or
                    type = 6
                or
                    type = 7
                )
                and
                    month(date) = $monthNumber 
                group by 
                    type, 
                    category
            ) as buf 
        left join 
            items 
        on 
            items.id=buf.category";

    $profitQuery = "
        select 
            types.name, 
            sum(sum) as sum 
        from (
            select 
                id, 
                type 
            from 
                checks 
            where (
                    type = 1 
                or 
                    type = 4 
                or 
                    type = 5
                or 
                    type = 9
            ) 
            and 
                month(date) = $monthNumber
        ) as checks 
        left join 
            moves 
        on 
            checks.id = moves.checkn 
        left join 
            types 
        on 
            type = types.id 
        group by 
            types.name;
    ";
    $profitRes = $base->query($profitQuery,true);

    $costQuery = "
        select 
            types.name, 
            sum(sum) as sum 
        from (
            select 
                id, 
                type 
            from 
                checks 
            where (
                    type = 2 
                or 
                    type = 3 
                or 
                    type = 6
                or
                    type = 7
                or
                    type = 8
            ) 
            and 
                month(date) = $monthNumber
        ) as checks 
        left join 
            moves 
        on 
            checks.id = moves.checkn 
        left join 
            types 
        on 
            type = types.id 
        group by 
            types.name;
    ";
    $costRes = $base->query($costQuery,true);

    $cashProfit = "select sum(sum) as profit from checks left join moves on checks.id = moves.checkn where month(date) <= $monthNumber and (type=1 or type=4 or type=5 or type=9)";
    $cashCost = "select sum(sum) as cost from checks left join moves on checks.id = moves.checkn where month(date) <= $monthNumber and (type=2 or type=3 or type=6 or type=7 or type=8)";

    $cashProfitRes = $base->query($cashProfit,true);
    $cashCostRes = $base->query($cashCost,true);
    $cash = $cashProfitRes[0]['profit'] - $cashCostRes[0]['cost'];

    $contragentQuery = "select date, contragents.name, sum from checks left join moves on checks.id=moves.checkn left join contragents on contragent=contragents.id where (type=1 or type=4 or type=5 or type=9) and month(date) = $monthNumber order by date;";
    
    $contragentRes = $base->query($contragentQuery,true);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Система учета финансов v_0.1</title>
        <style>
            body{
                margin: 0;
                padding: 0;
            }
            
            
            
            .titleSection{
                display: inline-block;
                vertical-align: top;
                background-color: antiquewhite;
                padding-left: 10px;
                padding-right: 20px;
                border: 1px solid gray;
                border-radius: 20px;
            }
            
            label{
                width: 150px;
                display: inline-block;
            }
            
            .sumProfit{
                display: inline-block;
                width: 70px;
                text-align: right;
                color: green;
            }
            
            .sumCost{
                display: inline-block;
                width: 70px;
                text-align: right;
                color: royalblue;
            }
            
            li{
                font-weight: bold;
            }
            
            .detailSection{
                display: inline-block;
                vertical-align: top;
                background-color: antiquewhite;
                padding-left: 10px;
                padding-right: 20px;
                border: 1px solid gray;
                border-radius: 20px;
            }
        </style>
    </head>
    <body>
        <a href="index.php">Назад</a> 
        <form action="reports.php">
            
            <select name="rmonth">
                <?php
                $monthmass = array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
                    forEach($monthmass as $key=>$value){
                ?>
                <option value="<?php echo($key + 1);?>" <?php if($monthNumber == ($key+1))echo('selected');?>><?php echo($value);?></option>
                <?php
                    }
                ?>
            </select>
            <input type="submit">
        </form>
        <br/>
        <div class="titleSection">
            
            <div class="profitSection">
                <h3>Приход</h3>
                <ul>
                    <?php
                        $globalProfit = 0;
                        forEach($profitRes as $key=>$value){
                            $sum = number_format($value['sum']/100,2);
                            print_r('<li><label>'.$value['name'].'</label><div class="sumProfit"> '.$sum.'</div></li>');
                            $globalProfit += $value['sum'];
                        }
                    ?>
                </ul>
                <h4>
                <?php
                    $sum = number_format($globalProfit/100,2);
                    print_r('Общий приход: '.$sum);
                ?>
                </h4>
            </div>
            <div class="costSection">
                <h3>Расход</h3>
                <ul>
                    <?php
                        $globalCost = 0;
                        forEach($costRes as $key=>$value){
                            $sum = number_format($value['sum']/100,2);
                            print_r('<li><label>'.$value['name'].'</label><div class="sumCost"> '.$sum.'</div></li>');
                            $globalCost += $value['sum'];
                        }
                    ?>
                </ul>
                <h4>
                <?php
                    $sum = number_format($globalCost/100,2);
                    print_r('Общий расход: '.$sum);
                ?>
                </h4>
            </div>
            <h3>
            <?php
                $monthSum = $globalProfit - $globalCost;
                $sum = number_format($monthSum/100,2);
                print_r('Итоги месяца: '.$sum);
            ?>
            </h3>
            <h3>
            <?php
                $sum = number_format($cash/100,2);
                print_r('Наличка на конец месяца: '.$sum);
            ?>
            </h3>
        </div>
        <div class="detailSection">
            <div class="categoryProfit">
                <h3>Доход по контрагентам</h3>
                <ul>
                    <?php
                        $globalProfit = 0;
                        forEach($contragentRes as $key=>$value){
                            $sum = number_format($value['sum']/100,2);
                            print_r('<li><label>'.$value['name'].'</label><div class="sumProfit"> '.$sum.'</div></li>');
                            $globalProfit += $value['sum'];
                        }
                    ?>
                </ul>
                <h4>
                <?php
                    $sum = number_format($globalProfit/100,2);
                    print_r('Общий доход: '.$sum);
                ?>
                </h4>
            </div>
            <div class="categoryCost">
                <h3>Расход по категориям</h3>
                <ul>
                    <?php
                        $globalCost = 0;
                        forEach($categoryRes as $key=>$value){
                            $sum = number_format($value['sum']/100,2);
                            print_r('<li><label>'.$value['name'].'</label><div class="sumCost"> '.$sum.'</div></li>');
                            $globalCost += $value['sum'];
                        }
                    ?>
                </ul>
                <h4>
                <?php
                    $sum = number_format($globalCost/100,2);
                    print_r('Общий расход: '.$sum);
                ?>
                </h4>
            </div>
        </div>
    </body>
</html>