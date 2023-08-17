<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\ChessTournament\PlayerTable;
use Bitrix\ChessTournament\GameTable;

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
                        <a class="nav-link link-primary" href="index.php">Турнирная таблица</a>
                    </li>
                    <li class="nav-item h5">
                        <a class="nav-link link-secondary" href="players.php">Участники</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                <table class="table table-bordered" style="table-layout: fixed;">
                    <col class="w-25">
                    <tr class="text-center">
                        <td class="text-start">Участники</td>
                        <?php
                        $i = 1;
                        while ($i <= count($arPlayers)) {
                        ?>
                            <td>
                                <?php
                                echo $i++;
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td>Очки</td>
                        <td>Место</td>
                    </tr>
                    <?php
                    $arScopes = GameTable::getScopes($arPlayers);
                    $arScopesValues = array_values($arScopes);
                    foreach ($arPlayers as $key => $player) {
                    ?>
                        <tr>
                            <td class="text-start">
                                <?php
                                echo $key + 1;
                                echo '. ';
                                echo $player['FIO'];
                                ?>.
                            </td>
                            <?php
                            $i = 1;
                            while ($i <= count($arPlayers)) {
                            ?>
                                <td class="<?php if ($i == $key + 1) {
                                                echo 'table-primary';
                                            } ?>">
                                    <small>
                                        <?php
                                        echo GameTable::getResultByPlayersId($player['ID'], $arPlayers[$i - 1]);
                                        $i++;
                                        ?>
                                    </small>
                                </td>
                            <?php
                            }
                            ?>
                            <td>
                                <?php
                                echo $arScopes[$player['ID']];
                                ?>
                            </td>
                            <td>
                                <?php
                                $index = array_search($arScopes[$player['ID']], $arScopesValues);
                                echo $index + 1;
                                unset($arScopesValues[$index]);
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Добавить партию</h5>
                        <form action="handler/add_game.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Белые</label>
                                <select class="form-select" name="white_player_id">
                                    <option selected></option>
                                    <?php
                                    foreach ($arPlayers as $value) {
                                    ?>
                                        <option value="<?php echo $value['ID'] ?>"><?php echo $value['FIO'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Черные</label>
                                <select class="form-select" name="black_player_id">
                                    <option selected></option>
                                    <?php
                                    foreach ($arPlayers as $value) {
                                    ?>
                                        <option value="<?php echo $value['ID'] ?>"><?php echo $value['FIO'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted">Выиграли</h6>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="result" id="resultWhite" value="white">
                                    <label class="form-check-label" for="resultWhite">
                                        Белые
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="result" id="resultBlack" value="black">
                                    <label class="form-check-label" for="resultBlack">
                                        Черные
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="result" id="resultDraw" value="draw">
                                    <label class="form-check-label" for="resultDraw">
                                        Ничья
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="backurl" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <button class="btn btn-primary" type="submit">
                                Сохранить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
?>