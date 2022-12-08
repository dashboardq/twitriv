<?php

namespace app\models;

use mavoc\core\Model;

class Restriction extends Model {
    public static $table = 'restrictions';

    public static function fullAccess($user_id, $return_type = 'all') {
        $args = [];
        $args['user_id'] = $user_id;
        $args['view_credits'] = 10000;
        $args['view_credits_used'] = 0;
        $args['premium_level'] = 10;
        $args['view_credits_reset_at'] = '2038-01-01 10:00:00';
        $restriction = new Restriction($args);

        if($return_type == 'data') {
            return $restriction->data;
        } else {
            return $restriction;
        }
    }
}
