<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>21_6_module</title>
    <link rel="stylesheet" href="./src/css/header.css">
</head>

<body>
    <div class="flex">
        <div class="header" id="header">
            <nav class="menu">
                <ul>
                    <li><a href="#header">home</a></li>
                    <li><a href="#main_part_2">currency rate</a></li>
                </ul>
            </nav>
        </div>

        <div class="main_1">
            <?php
            include 'main_1.php';
            ?>
        </div>

        <div class="main_2" id="main_part_2">
            <?php
            include 'main_2.php';
            ?>
            <?php
            include 'get_valute_rates.php'
            ?>
        </div>
    </div>
    <script src="./src/js/clear_table.js"></script>
    <script>
        var rates_to_js = <?php echo json_encode($rates_valute_and_error_message); ?>;
    </script>
    <script src="./src/js/valute_table.js"></script>

</body>

</html>