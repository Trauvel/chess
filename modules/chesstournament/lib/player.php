<?php

namespace Bitrix\ChessTournament;

use \Bitrix\Main\Entity;

class PlayerTable extends Entity\DataManager
{

    public static function getTableName()
    {
        return 'chesstournament_players';
    }

    public static function getConnectionName()
    {
        return 'default';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('FIO', array(
                'required' => true,
            )),
        );
    }
}
