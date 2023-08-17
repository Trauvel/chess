<?php

namespace Bitrix\ChessTournament;

use \Bitrix\Main\Entity;

class GameTable extends Entity\DataManager
{
    static $statuses = [
        'white',
        'draw',
        'black',
    ];

    public static function getTableName()
    {
        return 'chesstournament_games';
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
            new Entity\IntegerField('WHITE_PLAYER_ID', array(
                'required' => true,
            )),
            new Entity\IntegerField('BLACK_PLAYER_ID', array(
                'required' => true,
            )),
            new Entity\EnumField('SCOPE', array(
                'required' => true,
                'values' => self::$statuses,
                'validation' => function () {
                    return array(
                        function ($value) {
                            if (in_array($value, self::$statuses)) {
                                return true;
                            }
                        }
                    );
                }
            )),
            new Entity\ReferenceField(
                'WHITE_PLAYER',
                'Bitrix\ChessTournament\PlayerTable',
                array('=this.WHITE_PLAYER_ID' => 'ref.ID'),
            ),
            new Entity\ReferenceField(
                'BLACK_PLAYER',
                'Bitrix\ChessTournament\PlayerTable',
                array('=this.BLACK_PLAYER_ID' => 'ref.ID'),
            ),
        );
    }

    public static function getGamesByPlayerId($player_id = null)
    {
        if (!$player_id) return [];

        return self::getList(array(
            'filter' => array(
                'LOGIC' => 'OR',
                array(
                    '=WHITE_PLAYER_ID' => $player_id,
                ),
                array(
                    '=BLACK_PLAYER_ID' => $player_id,
                )
            )
        ))->fetchAll();
    }

    public static function getGamesByPlayersId($first_player_id = null, $second_player_id = null)
    {
        if (!$first_player_id || !$second_player_id) return [];

        return self::getList(array(
            'filter' => array(
                'LOGIC' => 'OR',
                array(
                    '=WHITE_PLAYER_ID' => $first_player_id,
                    '=BLACK_PLAYER_ID' => $second_player_id,
                ),
                array(
                    '=WHITE_PLAYER_ID' => $second_player_id,
                    '=BLACK_PLAYER_ID' => $first_player_id,
                )
            )
        ))->fetchAll();
    }

    public static function getResultByPlayersId($first_player_id = null, $second_player_id = null, $result_for_first = true)
    {
        if (!$first_player_id || !$second_player_id) return '';

        $arGames = self::getGamesByPlayersId($first_player_id, $second_player_id);
        $result = '';

        foreach ($arGames as $game) {
            switch ($game['SCOPE']) {
                case 'white':
                    if($result_for_first && $game['WHITE_PLAYER_ID'] == $first_player_id){
                        $result .= '1 / ';
                    }else{
                        $result .= '0 / ';
                    }
                    break;

                case 'draw':
                    $result .= '0.5 / ';
                    break;

                case 'black':
                    if($result_for_first && $game['BLACK_PLAYER_ID'] == $first_player_id){
                        $result .= '1 / ';
                    }else{
                        $result .= '0 / ';
                    }
                    break;
            }
        }

        return $result;
    }

    public static function getScopeByPlayerId($player_id = null)
    {
        if (!$player_id) return 0;

        $arGames = self::getGamesByPlayerId($player_id);
        $scope = 0;

        foreach ($arGames as $game) {
            switch ($game['SCOPE']) {
                case 'white':
                    if($game['WHITE_PLAYER_ID'] == $player_id){
                        $scope += 1;
                    }
                    break;

                case 'draw':
                    $scope += 0.5;
                    break;

                case 'black':
                    if($game['BLACK_PLAYER_ID'] == $player_id){
                        $scope += 1;
                    }
                    break;
            }
        }

        return $scope;
    }

    public static function getScopes($arPlayers = null)
    {
        if (!$arPlayers) return [];
        $arScopes = [];

        foreach ($arPlayers as $player) {
            $arScopes[$player['ID']] = self::getScopeByPlayerId($player['ID']);
        }

        arsort($arScopes);
        return $arScopes;
    }
}
