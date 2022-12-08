<?php

namespace app\controllers;

use app\models\Bookmark;
use app\models\Todo;

use app\services\TwitterService;

class AjaxController {
    public function bookmarkCreate($req, $res) {
        $val = $req->val('data', [
            'tweet_id' => ['required'],
            'tweet_name' => ['required'],
            'tweet_username' => ['required'],
            'tweet_content' => ['required'],
            'note' => ['sometimes'],
        ]);

        $search = "";
        $search .= $val['tweet_id'];
        $search .= "\n";
        $search .= $val['tweet_name'];
        $search .= "\n";
        $search .= $val['tweet_username'];
        $search .= "\n";
        $search .= $val['tweet_content'];
        $search .= "\n";
        $search .= $val['note'];

        $bookmark = Bookmark::by([
            'user_id' => $req->user_id,
            'tweet_id' => $val['tweet_id'],
        ]);
        if($bookmark) {
            $args = [];
            $args['tweet_content'] = $val['tweet_content'];
            $args['note'] = $val['note'];
            $args['search'] = $search;
            $bookmark = $bookmark->update($args);
        } else {
            $args = [];
            $args['user_id'] = $req->user_id;
            $args['tweet_id'] = $val['tweet_id'];
            $args['tweet_content'] = $val['tweet_content'];
            $args['note'] = $val['note'];
            $args['search'] = $search;
            $bookmark = Bookmark::create($args);
        }

        $twitter = new TwitterService($req->user_id);
        $response = $twitter->bookmarkCreate($val['tweet_id']);

        if(isset($response->errors)) {
            $messages = [];
            foreach($response->errors as $error) {
                $messages[] = $error->message;
            }

            $res->error($messages);
        }

        //echo '<pre>'; print_r($response); echo '</pre>';

        $note = $val['note'];

        $item = [];
        $item['tweet_id'] = $val['tweet_id'];
        $item['tweet_name'] = $val['tweet_name'];
        $item['tweet_username'] = $val['tweet_username'];
        $item['tweet_content'] = $val['tweet_content'];

        $res->view('ajax/bookmark-view', compact('item', 'note'));
    }

    public function interactCreate($req, $res) {
        $val = $req->val('data', [
            'tweet_id' => ['required'],
            'note' => ['required'],
        ]);

        $twitter = new TwitterService($req->user_id);
        $response = $twitter->replyCreate($val['tweet_id'], $val['note']);

        if(isset($response->errors)) {
            $messages = [];
            foreach($response->errors as $error) {
                $messages[] = $error->message;
            }

            $res->error($messages);
        }

        $item = [];
        $item['tweet_id'] = $val['tweet_id'];

        $res->view('ajax/interact-view', compact('item'));
    }

    public function likeCreate($req, $res) {
        $val = $req->val('data', [
            'tweet_id' => ['required'],
        ]);

        $twitter = new TwitterService($req->user_id);
        $response = $twitter->like($val['tweet_id']);

        if(isset($response->errors)) {
            $messages = [];
            foreach($response->errors as $error) {
                $messages[] = $error->message;
            }

            $res->error($messages);
        }

        // TODO: Update so that you can pass this as success (like you can with error).
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success']);
        exit;
    }

    public function todoCreate($req, $res) {
        $val = $req->val('data', [
            'tweet_id' => ['required'],
            'tweet_name' => ['required'],
            'tweet_username' => ['required'],
            'tweet_content' => ['required'],
            'note' => ['sometimes'],
        ]);

        $search = "";
        $search .= $val['tweet_id'];
        $search .= "\n";
        $search .= $val['tweet_name'];
        $search .= "\n";
        $search .= $val['tweet_username'];
        $search .= "\n";
        $search .= $val['tweet_content'];
        $search .= "\n";
        $search .= $val['note'];

        $todo = Todo::by([
            'user_id' => $req->user_id,
            'tweet_id' => $val['tweet_id'],
        ]);
        if($todo) {
            $args = [];
            $args['tweet_content'] = $val['tweet_content'];
            $args['note'] = $val['note'];
            $args['search'] = $search;
            $args['status'] = 'open';
            $todo = $todo->update($args);
        } else {
            $args = [];
            $args['user_id'] = $req->user_id;
            $args['tweet_id'] = $val['tweet_id'];
            $args['tweet_content'] = $val['tweet_content'];
            $args['note'] = $val['note'];
            $args['search'] = $search;
            $args['status'] = 'open';
            $todo = Todo::create($args);
        }


        $note = $val['note'];

        $item = [];
        $item['tweet_id'] = $val['tweet_id'];
        $item['tweet_name'] = $val['tweet_name'];
        $item['tweet_username'] = $val['tweet_username'];
        $item['tweet_content'] = $val['tweet_content'];

        $res->view('ajax/todo-view', compact('item', 'note'));
    }
}
