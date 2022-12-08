<?php

namespace app;

use app\models\Cycle;
use app\models\User;

use app\services\TwitterService;

class App {
    public function init() {
		// Run migrations if the user is not running a command line command and the db needs to be migrated.
        if(!defined('AO_CONSOLE_START') && ao()->env('DB_USE') && ao()->env('DB_INSTALL')) {
            ao()->once('ao_db_loaded', [$this, 'install']);
        }

        ao()->filter('ao_response_partial_args', [$this, 'cacheDate']);
        ao()->filter('ao_response_partial_args', [$this, 'cycles']);
    }

    public function cacheDate($vars, $view) {
        if($view == 'head' || $view == 'footer') {
            $vars['cache_date'] = '2022-11-16';
        }

        return $vars;
    }

    public function cycles($vars, $view) {
        if($view == 'cycles') {
            $req = $vars['req'];
            $cycles = Cycle::where('user_id', $req->user_id, 'data');

            // If there are no cycles, create the first batch of cycles.
            // Limit to 10.
            // Order by count descending.
            if(count($cycles) == 0) {
                $twitter = new TwitterService($req->user_id);
                $followings = $twitter->following(null, 10, 'followers_count', 'desc');

                foreach($followings as $following) {
                    $args = [];
                    $args['user_id'] = $req->user_id;
                    $args['name'] = '@' . $following['username'];
                    $args['link'] = '/' . $following['username'];
                    $args['type'] = 'username';
                    $args['twitter_id'] = $following['id'];
                    $args['twitter_name'] = $following['name'];
                    $args['twitter_username'] = $following['username'];
                    Cycle::create($args);
                }

                $cycles = Cycle::where('user_id', $req->user_id, 'data');
            }
            $vars['cycles'] = $cycles;
        }

        return $vars;
    }

    public function install() {
        try {
            $count = User::count();
        } catch(\Exception $e) {
            //ao()->command('work');
            ao()->command('mig init');
            ao()->command('mig up');

            // Redirect to home page now that the database is installed.
            header('Location: /');
            exit;
        }   
    } 

}
