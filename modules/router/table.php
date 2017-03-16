<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Routes list</title>

    <style media="screen">
        td{
            padding: 0px 20px;
        }
    </style>
</head>
<body>
    <table>
        <?php
        foreach($routes as $route){
            $action = is_callable($route['action']) ? 'Closure' : $route['action'];
            ?>
            <tr>
                <td><?php echo strtoupper($route['method']) ?></td>
                <td><?php echo $route['path'] ?></td>
                <td><?php echo $action ?></td>
            </tr>
            <?php
        }

        ?>
    </table>
</body>
</html>
