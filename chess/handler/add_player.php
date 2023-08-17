<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Bitrix\ChessTournament\PlayerTable;

if (!Loader::includeModule('chesstournament')) {
    die('Ошибка подключения модуля chesstournament');
}

if (
    $_POST['name'] &&
    $_POST['lastname']
) {
    if(is_numeric($_POST['id'])){
        $result = PlayerTable::update($_POST['id'], array(
            'FIO' => $_POST['lastname'] . ' ' . $_POST['name'],
        ));
    }else{
        $result = PlayerTable::add(array(
            'FIO' => $_POST['lastname'] . ' ' . $_POST['name'],
        ));
    }

    if ($result->isSuccess()) {
        header('Location: ' . $_POST['backurl']);
    }else{
        die('Ошибка работы с данными');
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
