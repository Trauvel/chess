<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\ChessTournament\GameTable;

if (!Loader::includeModule('chesstournament')) {
    die('Ошибка подключения модуля chesstournament');
}

if (
    is_numeric($_POST['white_player_id']) &&
    is_numeric($_POST['black_player_id']) &&
    $_POST['white_player_id'] != $_POST['black_player_id'] &&
    $_POST['result']
) {
    $result = GameTable::add(array(
        'WHITE_PLAYER_ID' => $_POST['white_player_id'],
        'BLACK_PLAYER_ID' => $_POST['black_player_id'],
        'SCOPE' => $_POST['result'],
    ));

    if ($result->isSuccess()) {
        header('Location: ' . $_POST['backurl']);
    }else{
        die('Ошибка работы с данными');
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');

