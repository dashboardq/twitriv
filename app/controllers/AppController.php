<?php

namespace app\controllers;

use app\models\Bookmark;
use app\models\Connection;
use app\models\Cycle;
use app\models\Profile;
use app\models\Restriction;
use app\models\Todo;
use app\models\UserSetting;

use app\services\TwitterService;

class AppController {
    public function home($req, $res) {
        $title = 'Home';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $show_connect = true;
            return compact('list', 'show_connect', 'title');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);
        
        $user_setting = UserSetting::by('user_id', $req->user_id, 'data');


        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $twitter = new TwitterService($req->user_id);
            $list = $twitter->home($query['pagination_token'], $user_setting['home_replies'], $user_setting['home_retweets']);
        } else {
            $twitter = new TwitterService($req->user_id);
            $list = $twitter->home($query['pagination_token'], $user_setting['home_replies'], $user_setting['home_retweets']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title', 'user_setting');
    }

    public function homePost($req, $res) {
        $val = $req->val($req->data, [
            'replies' => ['sometimes'],
            'retweets' => ['sometimes'],
        ]);

        $val = $req->clean($val, [
            'replies' => ['int'],
            'retweets' => ['int'],
        ]);

        $user_setting = UserSetting::by('user_id', $req->user_id);
        $user_setting->data['home_replies'] = $val['replies'];
        $user_setting->data['home_retweets'] = $val['retweets'];
        $user_setting->save();

        $res->redirect('/home');
    }

    public function bookmarks($req, $res) {
        $title = 'Bookmarks';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            $credits_needed = 20;
            $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);

            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], $credits_needed);
            $ids = $db->array($results);


            $list = $twitter->tweets($ids);

            $credits_used = count($list);
            $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);
        } else {
            $list = $twitter->bookmarks($query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function list($req, $res) {
        $title = 'List Timeline';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $params = $req->val('params', [
            'list_id' => ['required', 'int'],
        ], '/home');

        $query = $req->val('query', [
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        $info = $twitter->list($params['list_id']);
        $list = $twitter->listTimeline($params['list_id'], $query['pagination_token']);
        if(!$query['pagination_token']) {
            // Update the cycle list
            Cycle::call($req->user_id, $info->name, 'list', $info->id);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('info', 'list', 'query', 'show_connect', 'title');
    }


    public function lists($req, $res) {
        $title = 'Lists';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        $list = $twitter->lists();

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function mentions($req, $res) {
        $title = 'Mentions';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $credits_needed = 20;
        $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);
        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->mentions($query['pagination_token']);
        } else {
            $list = $twitter->mentions($query['pagination_token']);
        }

        $credits_used = count($list);
        $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function search($req, $res) {
        $title = 'Search';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $credits_needed = 20;
        $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);
        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            $list = $twitter->search($query['q'], $query['pagination_token']);

            if(!$query['pagination_token']) {
                // Update the cycle list
                Cycle::call($req->user_id, $query['q'], 'search');
            }
        } else {
            $list = [];
        }

        $credits_used = count($list);
        $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function settings($req, $res) {
        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $setting = UserSetting::by('user_id', $req->user_id, 'data');

        $res->fields['home_replies'] = $setting['home_replies'];
        $res->fields['home_retweets'] = $setting['home_retweets'];
        $res->fields['twitter_base'] = $setting['twitter_base'];
        $res->fields['twitter_new_tab'] = $setting['twitter_new_tab'];

        return ['title' => 'Settings', 'show_connect' => $show_connect];
    }
    public function settingsPost($req, $res) {
        $val = $req->val($req->data, [
            'home_replies' => ['sometimes'],
            'home_retweets' => ['sometimes'],
            'twitter_base' => ['required'],
            'twitter_new_tab' => ['sometimes'],
        ]);

        $val = $req->clean($val, [
            'home_replies' => ['int'],
            'home_retweets' => ['int'],
            'twitter_new_tab' => ['int'],
        ]);

        $user_setting = UserSetting::by('user_id', $req->user_id);
        $user_setting->data['home_replies'] = $val['home_replies'];
        $user_setting->data['home_retweets'] = $val['home_retweets'];
        $user_setting->data['twitter_base'] = $val['twitter_base'];
        $user_setting->data['twitter_new_tab'] = $val['twitter_new_tab'];
        $user_setting->save();

        $res->redirect('/settings');
    }


    public function todo($req, $res) {
        $title = 'Todos';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
        ]);

        $credits_needed = 20;
        $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);
        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            $db = ao()->db;
            $results = $db->query('SELECT tweet_id FROM todos WHERE user_id = ? AND MATCH(search) AGAINST(?) ORDER BY id DESC LIMIT ?', $req->user_id, $query['q'], $credits_needed);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
        } else {
            $db = ao()->db;
            $results = $db->query('SELECT tweet_id FROM todos WHERE user_id = ? ORDER BY id DESC LIMIT ?', $req->user_id, $credits_needed);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
        }

        $credits_used = count($list);
        $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function tweet($req, $res) {
        $title = 'Tweet Timeline';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $params = $req->val('params', [
            'username' => ['required', ['match' => '/[a-zA-Z0-9_]+/']],
            'tweet_id' => ['required', 'int'],
        ], '/home');

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $credits_needed = 20;
        $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);
        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->tweetTimeline($params['tweet_id'], $query['pagination_token']);
        } else {
            $list = $twitter->tweetTimeline($params['tweet_id'], $query['pagination_token']);
        }

        $credits_used = count($list);
        $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function username($req, $res) {
        $title = 'User Timeline';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $params = $req->val('params', [
            'username' => ['required', ['match' => '/[a-zA-Z0-9_]+/']],
        ], '/home');

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $credits_needed = 20;
        $credits_needed = ao()->hook('app_credits_needed', $credits_needed, $req, $res);
        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->userTimeline($params['username'], $query['pagination_token']);
        } else {
            $list = $twitter->userTimeline($params['username'], $query['pagination_token']);
        }

        $credits_used = count($list);
        $credits_used = ao()->hook('app_credits_used', $credits_used, $req, $res);

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        // Update the cycle list
        Cycle::call($req->user_id, $params['username']);

        return compact('list', 'query', 'show_connect', 'title');
    }

}
