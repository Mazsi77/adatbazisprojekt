<?php
    require_once ('./controller.php');

    $contr = new Controller();
    $data = $contr->setUp();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adatbázis projekt</title>

    <style>
        body{
            border: 0;
            padding: 1rem;
            margin: 0;
            max-width: 1200px;
            margin: auto;
            box-sizing: border-box;
        }
        .statusz{
            padding: 0.5em;
            color:white;
        }
        .siker{
            background-color: green;
            color: white;
        }
        .hiba{
            background-color: red;
        }
        .nincs{
            background-color: orange;
        }
        .kapcsolatok{
            display: flex;
            flex-direction: row;
            justify-content: space-between;

        }
    </style>
</head>
<body>
    <section class="kapcsolatok">
        <div class="kapcsolat">
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="mysqlConnect">
                <h3 class="nev">Mysql Kapcsolat</h3>
                <p class="statusz <?php echo (isset($_SESSION['mysql']) ? ($_SESSION['mysql']==true  ? 'siker' : 'hiba' ): 'nincs') ?>"><?php echo  $data["mysql"]?></p>
                <input type="submit" value="Kapcsolódás" <?php echo (isset($_SESSION['mysql']) ? ($_SESSION['mysql']==true  ? 'disabled' : '' ): '')?>>
            </form>
        </div>

        <div class="kapcsolat">
            <form action="actions.php" method="post">
                <input type="hidden" name="action" value="mongoConnect">
                <h3 class="nev">Mongo Kapcsolat</h3>
                <p class="statusz <?php echo (isset($_SESSION['mongo']) ? ($_SESSION['mongo']==true  ? 'siker' : 'hiba' ): 'nincs') ?>"><?php echo  $data["mongo"]?></p>
                <input type="submit" value="Kapcsolódás" <?php echo (isset($_SESSION['mongo']) ? ($_SESSION['mongo']==true  ? 'disabled' : '' ): '')?>>
            </form>
        </div>
    </section>
    <section class="adat">
        <h2>Csapat</h2>
        <form action="actions.php" method="post" class="bevitel">
            <input type="hidden" name="action" value="csapatBevitel">
            <label for="id">Id</label>
            <input type="number" name="id" min="0" max="65535" required>
            <label for="name">Név</label>
            <input type="text" name="name" required>
            <input type="submit" value="Submit">
        </form>
        <div class="mysql">
            <h3>Mysql</h3>
            <?php if($data['mysqlCsapatok']) :?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Név</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['mysqlCsapatok'] as $row): array_map('htmlentities', $row); ?>
                            <tr>
                                <td><?php echo implode('</td><td>', $row); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Nincs kapcsolat</p>
            <?php endif ?>
        </div>
        <div class="mongo">
            <h3>MongoDb</h3>
            <?php if($data['mongoCsapatok']) : 
                foreach ($data['mongoCsapatok'] as $document)
                {
                    echo '<p>' .json_encode($document) .'</p>';
                }
                else : ?>
                <p>Nincs kapcsolat</p>
            <?php endif ?>
        </div>
    </section>
    <section class="adat">
        <h2>Versenyző</h2>
        <form action="actions.php" method="post" class="bevitel">
            <input type="hidden" name="action" value="versenyzoBevitel">
            <label for="id">Id</label>
            <input type="number" name="id" min="0" max="65535" required>
            <label for="csapat_id">CsapatId</label>
            <input type="number" name="csapat_id" min="0" max="65535" required>
            <label for="name">Név</label>
            <input type="text" name="name" required>
            <label for="verseny_szam">Verseny_szam</label>
            <input type="number" name="verseny_szam" min="0" max="65535" required>
            <input type="submit" value="Submit">
        </form>
        <div class="mysql">
            <h3>Mysql</h3>
            <?php if($data['mysqlVersenyzok']) :?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Csapat Id </th>
                            <th>Név</th>
                            <th>Verseny szám</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['mysqlVersenyzok'] as $row): array_map('htmlentities', $row); ?>
                            <tr>
                                <td><?php echo implode('</td><td>', $row); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Nincs kapcsolat</p>
            <?php endif ?>
        </div>
        <div class="mongo">
            <h3>MongoDb</h3>
            <?php if($data['mongoVersenyzok']) : 
                foreach ($data['mongoVersenyzok'] as $document)
                {
                    echo '<p>' .json_encode($document) .'</p>';
                }
                else : ?>
                <p>Nincs kapcsolat</p>
            <?php endif ?>
        </div>
    </section>
</body>
</html>