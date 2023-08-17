<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;

use Bitrix\ChessTournament\GameTable;
use Bitrix\ChessTournament\PlayerTable;

Loc::loadMessages(__FILE__);

class chesstournament extends CModule
{

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID           = get_class($this);
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME         = Loc::getMessage('CHESSTOURNAMENT_NAME');
            $this->MODULE_DESCRIPTION  = Loc::getMessage('CHESSTOURNAMENT_DESCRIPTION');
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('CHESSTOURNAMENT_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function doInstall()
    {
        global $APPLICATION;

        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) {
            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('CHESSTOURNAMENT_INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('CHESSTOURNAMENT_INSTALL_TITLE') . ' «' . Loc::getMessage('CHESSTOURNAMENT_NAME') . '»',
            __DIR__ . '/step.php'
        );
    }

    public function installDB()
    {
        Loader::includeModule($this->MODULE_ID);

        /*
         * Создание таблицы chesstournament_games
         */
        $connection = GameTable::getConnectionName();
        $connection = Application::getConnection($connection);
        $instance = Base::getInstance('Bitrix\ChessTournament\GameTable');
        $tableName = $instance->getDBTableName();

        if (!$connection->isTableExists($tableName)) {
            $instance->createDBTable();
        }

        /*
         * Создание таблицы chesstournament_players
         */
        $connection = PlayerTable::getConnectionName();
        $connection = Application::getConnection($connection);
        $instance = Base::getInstance('Bitrix\ChessTournament\PlayerTable');
        $tableName = $instance->getDBTableName();

        if (!$connection->isTableExists($tableName)) {
            $instance->createDBTable();
        }

        /*
         * Очистка памяти
         */
        unset($connection);
        unset($instance);
        unset($tableName);
    }

    public function doUninstall()
    {
        global $APPLICATION;

        $this->uninstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('CHESSTOURNAMENT_UNINSTALL_TITLE') . ' «' . Loc::getMessage('CHESSTOURNAMENT_NAME') . '»',
            __DIR__ . '/unstep.php'
        );
    }

    public function uninstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        /*
         * Удаление таблицы chesstournament_games
         */
        $connection = GameTable::getConnectionName();
        $connection = Application::getConnection($connection);
        $instance = Base::getInstance('Bitrix\ChessTournament\GameTable');
        $tableName = $instance->getDBTableName();

        $connection->queryExecute('drop table if exists ' . $tableName);

        /*
         * Удаление таблицы chesstournament_players
         */
        $connection = PlayerTable::getConnectionName();
        $connection = Application::getConnection($connection);
        $instance = Base::getInstance('Bitrix\ChessTournament\PlayerTable');
        $tableName = $instance->getDBTableName();

        $connection->queryExecute('drop table if exists ' . $tableName);

        /*
         * Очистка памяти
         */
        unset($connection);
        unset($instance);
        unset($tableName);
    }
}
