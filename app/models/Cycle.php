<?php

namespace app\models;

use mavoc\core\Model;

class Cycle extends Model {
    public static $table = 'cycles';
    public static $order = ['updated_at' => 'desc'];
    public static $limit = 20;

    public static function call($user_id, $value, $type = 'username', $extra = '') {
        if($type == 'search' && $value) {
            $cycle = self::by(['user_id' => $user_id, 'name' => $value]); 
            if(!$cycle) {
                $args = [];
                $args['user_id'] = $user_id;
                $args['name'] = $value;
                $args['link'] = '/search?q=' . urlencode($value);
                $args['type'] = 'search';
                Cycle::create($args);
            } else {
                $cycle->update();
            }
        } elseif($type == 'list' && $value) {
            $cycle = self::by(['user_id' => $user_id, 'name' => $value]); 
            if(!$cycle) {
                $args = [];
                $args['user_id'] = $user_id;
                $args['name'] = $value;
                $args['link'] = '/list/' . $extra;
                $args['type'] = 'list';
                Cycle::create($args);
            } else {
                $cycle->update();
            }
        } elseif($type == 'username' && $value) {
            $cycle = self::by(['user_id' => $user_id, 'twitter_username' => $value]); 
            if(!$cycle) {
                $args = [];
                $args['user_id'] = $user_id;
                $args['name'] = '@' . $value;
                $args['link'] = '/' . $value;
                $args['twitter_username'] = $value;
                $args['type'] = 'username';
                Cycle::create($args);
            } else {
                $cycle->update();
            }
        }
    }

}
