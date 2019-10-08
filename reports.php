<?php
    require_once 'base.php';

    $base = new Base('localhost','root','glos2ar12','finance2019');

    $query = "
        SELECT 
            contragents.name, 
            buf.date, 
            buf.sum 
        FROM 
            contragents 
        RIGHT JOIN 
            (
                SELECT 
                    checks.contragent, 
                    checks.date AS date, 
                    sum(moves.sum) AS sum 
                FROM 
                    checks 
                LEFT JOIN 
                    moves 
                ON 
                    checks.id = moves.checkn 
                GROUP BY 
                    checks.contragent, 
                    checks.date
            ) AS buf 
        ON 
            contragents.id = buf.contragent
        ORDER BY
            buf.date
    ";
    $res = $base->query($query,true);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Система учета финансов v_0.1</title>
    </head>
    <body>
       <a href="index.php">Назад</a>
        <table>
            <thead>
                <tr>
                    <th>Дата</th><th>Товар</th><th>Стоимость</th><th>Остаток</th>
                </tr>
            </thead>
            <tbody>
                <?php
                forEach($res as $key=>$value){
                print_r('<tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['name'].'</td>
                            <td>'.($value['sum']/100).'</td>
                            <td></td>
                        </tr>');
                }
                ?>
            </tbody>
        </table>
    </body>
</html>