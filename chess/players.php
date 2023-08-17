<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\ChessTournament\PlayerTable;

if (!Loader::includeModule('chesstournament')) {
    die('Ошибка подключения модуля chesstournament');
}

$arPlayers = PlayerTable::getList()->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Шахматный турнир</title>
</head>

<body>
    <div class="container-fluid bg-primary bg-gradient text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 py-4">
                    <h1 class="display-1">Шахматный турнир</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="nav mb-4">
                    <li class="nav-item h5">
                        <a class="nav-link link-secondary" href="index.php">Турнирная таблица</a>
                    </li>
                    <li class="nav-item h5">
                        <a class="nav-link link-primary" href="players.php">Участники</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <a class="btn btn-outline-primary mb-3" href="player-add.php">Добавить участника</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <?php
                    foreach ($arPlayers as $key => $player) {
                    ?>
                        <tr>
                            <td>
                                <?php
                                echo $key + 1;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $player['FIO'];
                                ?>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-primary btn-sm" href="player-add.php?id=<?php echo $player['ID'] ?>">Редактировать</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
</body>

</html>