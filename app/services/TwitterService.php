<?php

namespace app\services;

use app\models\Connection;
use app\models\Profile;
use app\models\User;
use app\models\UserSetting;

use mavoc\core\Exception;
use mavoc\core\REST;

use DateTime;

class TwitterService {
    public $rest;
    public $twitter_id = '';
    public $url_base;
    public $refresh_count = 0;

    public $user;
    public $user_id;
    public $user_settings;
    public $profile;
    public $connection;

    // For Manual Troubleshooting
    public $refreshing = false;

    public $timeline_args = [
        'expansions' => 'author_id,attachments.media_keys,in_reply_to_user_id,referenced_tweets.id,referenced_tweets.id.author_id',
        'media.fields' => 'preview_image_url,url',
        'tweet.fields' => 'attachments,conversation_id,created_at,entities,public_metrics,referenced_tweets',
        'user.fields' => 'verified,profile_image_url',

        // Only load 20 to keep the response times quick.
        'max_results' => 20,
    ];


    public function __construct($user_id) {
        $this->user_id = $user_id;
        $this->user = User::find($this->user_id);

        $this->user_settings = UserSetting::by('user_id', $user_id);
        $this->profile = Profile::find($this->user_settings->data['profile_id']);
        $this->connection = Connection::find($this->profile->data['connection_id']);
        $this->rest = new REST($this->connection->data['values']['access_token']);
        //$this->rest = new REST('bad_token_test');
        $this->twitter_id = $this->profile->data['twitter_id'];

        $this->url_base = ao()->env('TWITTER_URL');
    }

    public function bookmarks($pagination_token = '') {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/bookmarks';
        $url .= '?' . http_build_query($this->timeline_args);
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function bookmarkCreate($tweet_id) {
        $twitter_id = $this->twitter_id;

        $headers = ['Content-Type: application/json'];
 
        $data = [];
        $data['tweet_id'] = $tweet_id;
        $data = json_encode($data);

        $url = '/2/users/' . $twitter_id . '/bookmarks';
        $response = $this->post($url, $data, $headers);

        return $response;
    }

    public function following($twitter_id = null, $out_limit = 100, $sort_by = null, $direction = 'asc') {
        if(!$twitter_id) {
            $twitter_id = $this->twitter_id;
        }
        
        $args = [];
        $args['user.fields'] = 'public_metrics';
        $args['max_results'] = 1000;

        $url = '/2/users/' . urlencode($twitter_id) . '/following';
        $url .= '?' . http_build_query($args);
        $response = $this->get($url);

        $output = [];
        foreach($response->data ?? [] as $item) {
            $values = [];
            $values['id'] = $item->id;
            $values['name'] = $item->name;
            $values['username'] = $item->username;
            $values['followers_count'] = $item->public_metrics->followers_count;
            $values['following_count'] = $item->public_metrics->following_count;
            $values['tweet_count'] = $item->public_metrics->tweet_count;
            $values['listed_count'] = $item->public_metrics->listed_count;
            $output[] = $values;
        }

        if($sort_by) {
			// Create sort array that matches order of the $output array and then sort $output array by $sort array
			$sort = [];
			foreach($output as $item) {
				$sort[] = $item[$sort_by];
			}

            if($direction == 'desc') {
                array_multisort($sort, SORT_DESC, $output);
            } else {
                array_multisort($sort, SORT_ASC, $output);
            }
        }

        if($out_limit) {
            $output = array_slice($output, 0, $out_limit);
        }

        return $output;
    }

    public function home($pagination_token = '', $replies = 0, $retweets = 0) {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/timelines/reverse_chronological';
        $url .= '?' . http_build_query($this->timeline_args);
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }
        if(!$replies && !$retweets) {
            $url .= '&' . http_build_query(['exclude' => 'replies,retweets']);
        } elseif(!$replies) {
            $url .= '&' . http_build_query(['exclude' => 'replies']);
        } elseif(!$retweets) {
            $url .= '&' . http_build_query(['exclude' => 'retweets']);
        }

        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function like($tweet_id) {
        $twitter_id = $this->twitter_id;

        $headers = ['Content-Type: application/json'];
 
        $data = [];
        $data['tweet_id'] = $tweet_id;
        $data = json_encode($data);

        $url = '/2/users/' . $twitter_id . '/likes';
        $response = $this->post($url, $data, $headers);

        return $response;
    }

    public function me() {
    }

    public function mentions($pagination_token = '') {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/mentions';
        $url .= '?' . http_build_query($this->timeline_args);
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($data);
        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function replyCreate($tweet_id, $text) {
        $twitter_id = $this->twitter_id;

        $headers = ['Content-Type: application/json'];
 
        $data = [];
        $data['text'] = $text;
        $data['reply'] = [];
        $data['reply']['in_reply_to_tweet_id'] = $tweet_id;
        $data = json_encode($data);

        $url = '/2/tweets';
        $response = $this->post($url, $data, $headers);

        return $response;
    }

    public function search($query = '', $pagination_token = '') {
        if($query == '') {
            return [];
        }

        $args = $this->timeline_args;
        $url = '/2/tweets/search/recent';
        $url .= '?' . http_build_query($args);
        $url .= '&' . 'query=' . urlencode($query);
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function get($url) {
        $url = $this->url_base . $url;
        $output = $this->rest->get($url);
        /*
        echo '<br>';
        echo 'get response:';
        echo '<br>';
        echo '<pre>'; print_r($output); echo '</pre>';
        // */

        if(isset($output->status) && $output->status == 401) {
            /* May also need to output the $this->rest->get values
            echo 'refresh';
            echo '<br>';
            echo 'url: ' . $url;
            echo '<br>';
            echo 'Before:';
            echo '<br>';
            echo '<pre>'; print_r($this->rest->headers); echo '</pre>';
             //*/
            $this->refresh();
            /*
            echo 'After:';
            echo '<br>';
            echo '<pre>'; print_r($this->rest->headers); echo '</pre>';

            echo '<br>';
            echo '<br>';
            echo "curl --request GET --url '" . $url . "' --header 'Authorization: Bearer " . $this->rest->api_key . "'";
            echo '<br>';
            echo '<br>';
            sleep(15);
             //*/
            $output = $this->get($url);
            /*
            echo 'Refresh output:';
            echo '<br>';
            echo '<pre>'; print_r($output); echo '</pre>';
            //*/
            //die;
        }
        return $output;
    }

    public function list($list_id) {
        $url = '/2/lists/' . $list_id;
        $data = $this->get($url);

        return $data->data;
    }

    public function listTimeline($list_id, $pagination_token = '') {
        $url = '/2/lists/' . $list_id . '/tweets';
        $url .= '?' . http_build_query($this->timeline_args);
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function lists($pagination_token = '') {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/owned_lists';
        //$url .= '?' . http_build_query($this->timeline_args);
        if($pagination_token) {
            $url .= '?' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        return $data->data;
    }

    public function post($url, $data, $headers) {
        $url = $this->url_base . $url;
        $output = $this->rest->post($url, $data, $headers);

        if(isset($output->status) && $output->status == 401) {
            $this->refresh();
            $output = $this->post($url, $data, $headers);
        }
        return $output;
    }

    public function processTimeline($data, $include_parent = true) {
        $list = [];

        //echo '<pre>'; print_r($data); echo '</pre>';
        //die;

        foreach($data->data ?? [] as $i => $item) {
            $list[] = $item;
            $list[$i]->created = new DateTime($item->created_at);
            ////preg_match('|(.*)(https://t.co/[a-zA-Z0-9]+)$|s', $item->text, $matches);
            ////if(count($matches)) {
                ////$list[$i]->text = $matches[1];
                ////$list[$i]->last_link = $matches[2];
            ////}
            $list[$i]->text = $this->processTweetText($item);
        }

        $users = [];
        foreach($data->includes->users ?? [] as $user) {
            $users[$user->id] = $user;
        }

        $media = [];
        foreach($data->includes->media ?? [] as $medi) {
            $media[$medi->media_key] = $medi;
            if($medi->type == 'animated_gif') {
                $video_id = str_replace('https://pbs.twimg.com/tweet_video_thumb/', '', $medi->preview_image_url);
                $video_id = str_replace('.jpg', '', $video_id);
                $media[$medi->media_key]->video_url = 'https://video.twimg.com/tweet_video/' . $video_id . '.mp4';
            }
        }

        $tweets = [];
        foreach($data->includes->tweets ?? [] as $twee) {
            $tweets[$twee->id] = $twee;
        }


        foreach($list as $i => $item) {
            $list[$i]->author = $users[$item->author_id];
            $list[$i]->media = [];
            if(isset($item->attachments->media_keys)) {
                foreach($item->attachments->media_keys as $j => $key) {
                    $list[$i]->media[] = $media[$key];
                }
            }

            $list[$i]->replacements = [];
            $list[$i]->links = [];
            if(isset($item->entities->urls) && count($item->entities->urls) && isset($item->entities->urls[0]->title)) {
                //$list[$i]->links[] = $item->entities->urls[0];
                $url = $item->entities->urls[0];
                //echo '<pre>'; print_r($url); echo '</pre>';
                $link = new \stdClass();
                $link->url = $url->unwound_url;
                $link->image = $url->images[0]->url ?? '';
                $link->title = $url->title;
                $link->description = $url->description ?? '';
                $link->display_url = $url->display_url;
                if($link->image) {
                    $list[$i]->links[] = $link;
                } else {
                    //$list[$i]->replacements[];
                }
                //echo '<pre>'; print_r($list[$i]); echo '</pre>';
            }

            $list[$i]->replied_tweet = false;
            $list[$i]->retweeted_tweet = false;
            $list[$i]->quoted_tweet = false;
            if(isset($item->referenced_tweets) && count($item->referenced_tweets)) {
                if($include_parent && $item->referenced_tweets[0]->type == 'replied_to') {
                    $replied_tweet = $tweets[$item->referenced_tweets[0]->id];
                    $list[$i]->replied_tweet = true;
                    $list[$i]->replied_id = $replied_tweet->id;
                    $list[$i]->replied_author = $users[$replied_tweet->author_id];
                    $list[$i]->replied_created_at = $replied_tweet->created_at;
                    $list[$i]->replied_created = new DateTime($list[$i]->replied_created_at);
                    $list[$i]->replied_text = $replied_tweet->text;
                    $list[$i]->replied_public_metrics = $replied_tweet->public_metrics;
                    $list[$i]->replied_links = [];
                    $list[$i]->replied_media = [];

                    $list[$i]->replied_show_bookmark = false;
                    $list[$i]->replied_bookmark = '';

                    $list[$i]->replied_show_todo = false;
                    $list[$i]->replied_todo = '';
                } elseif($item->referenced_tweets[0]->type == 'retweeted') {
                    $list[$i]->id = $list[$i]->id;
                    $list[$i]->retweeted_tweet = true;
                    $list[$i]->retweeter = $list[$i]->author;
                    $list[$i]->author = $users[$tweets[$item->referenced_tweets[0]->id]->author_id];
                    $list[$i]->text = $tweets[$item->referenced_tweets[0]->id]->text;
                } elseif($item->referenced_tweets[0]->type == 'quoted') {
                    $quoted_tweet = $tweets[$item->referenced_tweets[0]->id];
                    $list[$i]->quoted_tweet = true;
                    $list[$i]->quoted_id = $quoted_tweet->id;
                    $list[$i]->quoted_author = $users[$quoted_tweet->author_id];
                    $list[$i]->quoted_created_at = $quoted_tweet->created_at;
                    $list[$i]->quoted_created = new DateTime($list[$i]->quoted_created_at);
                    $list[$i]->quoted_text = $quoted_tweet->text;
                    $list[$i]->quoted_media = [];

                    /* The media keys don't appear to end up in the media list
                    if(isset($quoted_tweet->attachments->media_keys)) {
                        foreach($quoted_tweet->attachments->media_keys as $j => $key) {
                            $list[$i]->quoted_media[] = $media[$key];
                        }
                    }
                     */
                }
            }
        }

        if(isset($data->meta->previous_token) && count($list)) {
            $list[count($list) - 1]->previous_token = http_build_query(['pagination_token' => $data->meta->previous_token]);
        }
        if(isset($data->meta->next_token) && count($list)) {
            $list[count($list) - 1]->next_token = http_build_query(['pagination_token' => $data->meta->next_token]);
        }
        //echo '<pre>'; print_r($list); echo '</pre>';
        //die;

        return $list;
    }

    public function processTweetText($item) {
        $replace = [];
        $output = $item->text;

        if(isset($item->entities->mentions)) {
            foreach($item->entities->mentions as $mention) {
                $replace[$mention->start] = [
                    'start' => $mention->start,
                    'end' => $mention->end,
                    'link' => '/' . $mention->username,
                    'text' => '@' . $mention->username,
                ];
            }
        }

        if(isset($item->entities->urls)) {
            foreach($item->entities->urls as $url) {
                if(isset($url->unwound_url)) {
                    $replace[$url->start] = [
                        'start' => $url->start,
                        'end' => $url->end,
                        'link' => $url->unwound_url,
                        'text' => $url->display_url,
                    ];
                } elseif(isset($url->media_key)) {
                    $replace[$url->start] = [
                        'start' => $url->start,
                        'end' => $url->end,
                        'remove' => true,
                    ];
                } else {
                    $replace[$url->start] = [
                        'start' => $url->start,
                        'end' => $url->end,
                        'remove' => true,
                    ];
                }
            }
        }

        krsort($replace);

        foreach($replace as $rep) {
            if(isset($rep['remove'])) {
                $str = '';
            } else {
                $str = '<a href="' . _esc($rep['link']) . '">' . _esc($rep['text']) . '</a>';
            }

            // Does not handle utf8
            //$output = substr_replace($output, $str, $rep['start'], $rep['end'] - $rep['start']);
            $part1 = mb_substr($output, 0, $rep['start']);
            $part2 = $str;
            $part3 = mb_substr($output, $rep['end']);
            $output = $part1 . $part2 . $part3;
        }

        //echo '<pre>'; print_r($item); echo '</pre>';
        //echo '<pre>'; print_r($replace); echo '</pre>';
        //echo '<br>';
        //echo $output;
        //die;

        return $output;
    }

    public function processTweetTimeline($tweet_data, $data) {
        $list = $this->processTimeline($data, false);

        //echo '<pre>'; print_r($tweet_data);
        //die;

        //echo '<pre>'; print_r($data);
        //die;


        if(isset($tweet_data->data)) {
            $item = $tweet_data->data;
            $item->created = new DateTime($item->created_at);
            //preg_match('|(.*)(https://t.co/[a-zA-Z0-9]+)$|s', $item->text, $matches);
            //if(count($matches)) {
                //$item->text = $matches[1];
                //$item->last_link = $matches[2];
            //}
            $item->text = $this->processTweetText($item);

            $users = [];
            foreach($tweet_data->includes->users ?? [] as $user) {
                $users[$user->id] = $user;
            }

            $media = [];
            foreach($tweet_data->includes->media ?? [] as $medi) {
                $media[$medi->media_key] = $medi;
                if($medi->type == 'animated_gif') {
                    $video_id = str_replace('https://pbs.twimg.com/tweet_video_thumb/', '', $medi->preview_image_url);
                    $video_id = str_replace('.jpg', '', $video_id);
                    $media[$medi->media_key]->video_url = 'https://video.twimg.com/tweet_video/' . $video_id . '.mp4';
                }
            }

            $tweets = [];
            foreach($tweet_data->includes->tweets ?? [] as $twee) {
                $tweets[$twee->id] = $twee;
            }

            $item->author = $users[$item->author_id];
            $item->media = [];
            if(isset($item->attachments->media_keys)) {
                foreach($item->attachments->media_keys as $j => $key) {
                    $item->media[] = $media[$key];
                }
            }

            $item->links = [];
            if(isset($item->entities->urls) && count($item->entities->urls) && isset($item->entities->urls[0]->title)) {
                //$list[$i]->links[] = $item->entities->urls[0];
                $url = $item->entities->urls[0];
                //echo '<pre>'; print_r($url); echo '</pre>';
                $link = new \stdClass();
                $link->url = $url->unwound_url;
                $link->image = $url->images[0]->url ?? '';
                $link->title = $url->title;
                $link->description = $url->description ?? '';
                $link->display_url = $url->display_url;
                $item->links[] = $link;
                //echo '<pre>'; print_r($list[$i]); echo '</pre>';
            }

            $item->replied_tweet = false;
            $item->retweeted_tweet = false;
            $item->quoted_tweet = false;
            if(isset($item->referenced_tweets) && count($item->referenced_tweets)) {
                if($item->referenced_tweets[0]->type == 'replied_to') {
                    $replied_tweet = $tweets[$item->referenced_tweets[0]->id];
                    $item->replied_tweet = true;
                    $item->replied_id = $replied_tweet->id;
                    $item->replied_author = $users[$replied_tweet->author_id];
                    $item->replied_created_at = $replied_tweet->created_at;
                    $item->replied_created = new DateTime($item->replied_created_at);
                    $item->replied_text = $replied_tweet->text;
                    $item->replied_public_metrics = $replied_tweet->public_metrics;
                    $item->replied_links = [];
                    $item->replied_media = [];

                    $item->replied_show_bookmark = false;
                    $item->replied_bookmark = '';

                    $item->replied_show_todo = false;
                    $item->replied_todo = '';
                } elseif($item->referenced_tweets[0]->type == 'retweeted') {
                    $item->id = $tweets[$item->referenced_tweets[0]->id]->id;
                    $item->retweeted_tweet = true;
                    $item->retweeter = $item->author;
                    $item->author = $users[$tweets[$item->referenced_tweets[0]->id]->author_id];
                    $item->text = $tweets[$item->referenced_tweets[0]->id]->text;
                } elseif($item->referenced_tweets[0]->type == 'quoted') {
                    $quoted_tweet = $tweets[$item->referenced_tweets[0]->id];
                    $item->quoted_tweet = true;
                    $item->quoted_id = $quoted_tweet->id;
                    $item->quoted_author = $users[$quoted_tweet->author_id];
                    $item->quoted_created_at = $quoted_tweet->created_at;
                    $item->quoted_created = new DateTime($item->quoted_created_at);
                    $item->quoted_text = $quoted_tweet->text;
                    $item->quoted_media = [];

                    /* The media keys don't appear to end up in the media list
                    if(isset($quoted_tweet->attachments->media_keys)) {
                        foreach($quoted_tweet->attachments->media_keys as $j => $key) {
                            $item->quoted_media[] = $media[$key];
                        }
                    }
                     */
                }
            }

            array_unshift($list, $item);
        }


        return $list;
    }

    public function refresh() {
        $this->refresh_count++;
        if($this->refresh_count >= 3) {
            $this->connection->delete($this->connection->id);
            throw new Exception('There was a problem connecting with Twitter. Please try connecting to Twitter again. If the issue continues, please contact support.', '/home');
        }

        $authentication = base64_encode(ao()->env('TWITTER_CLIENT_ID') . ':' . ao()->env('TWITTER_CLIENT_SECRET'));
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $authentication,
        ];
        $rest = new REST($headers);    
        // Make a curl call.   
        $url = ao()->env('TWITTER_URL_TOKEN');
        $post = [
            'refresh_token' => $this->connection->data['values']['refresh_token'],
            'grant_type' => 'refresh_token',
            'client_id' => ao()->env('TWITTER_CLIENT_ID'),
        ];
        // For some reason, it doesn't work when you passed in as an array so converting it to query args.
        // (I saw someone else on the Twitter forums describe this same issue.)
        // I don't have time right now to see what the difference is with the way curl makes the call.
        // I'm guessing it has something to do with explicitly calling the Content-Type header above.
        $values = http_build_query($post);
        $access = $rest->post($url, $values, [], true);
        //echo '<pre>'; print_r($access);die;
        //$access = $rest->post($url, $post, [], true);
        if(!isset($access['access_token'])) {
            $this->connection->delete($this->connection->id);
            throw new Exception('There was a problem connecting with Twitter. Try connecting again and if the problem continues, please contact support.', '/home');
        }

        $this->rest = new REST($access['access_token']);
        $response = $this->rest->get(ao()->env('TWITTER_URL') . '/2/users/me');
        if(!isset($response->data->id)) {
            throw new Exception('There was a problem accessing user info. Please try again and if the problem happens again, please contact support.', '/home');
        }

        $this->profile->data['twitter_name'] = $response->data->name;
        $this->profile->data['twitter_username'] = $response->data->username;
        $this->profile->save();

        $this->connection->data['user_id'] = $this->twitter_id;
        $this->connection->data['twitter_id'] = $response->data->id;
        $this->connection->data['data'] = $access;
        $this->connection->data['encrypted'] = 0;
        $this->connection->save();
    }

    public function tweets($ids) {
        $args = $this->timeline_args;
        unset($args['max_results']);
        $url = '/2/tweets';
        $url .= '?' . http_build_query($args);
        $url .= '&' . 'ids=' . implode(',', $ids);

        $data = $this->get($url);
        //echo '<pre>'; print_r($data); echo '</pre>'; die;

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($data);
        //echo '<pre>'; print_r($list);
        //die;

        return $list;
    }

    public function tweetTimeline($tweet_id, $pagination_token = '') {
        $args = $this->timeline_args;
        unset($args['max_results']);
        $url = '/2/tweets/' . $tweet_id;
        $url .= '?' . http_build_query($args);

        $tweet_data = $this->get($url);

        $args = $this->timeline_args;
        unset($args['max_results']);
        $url = '/2/tweets/search/recent';
        $url .= '?' . http_build_query($args);
        $url .= '&' . 'query=in_reply_to_tweet_id:' . $tweet_id;
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        //$url = '/2/tweets/search/recent';
        //$url .= '?' . 'query=in_reply_to_tweet_id:' . $tweet_id;
        $data = $this->get($url);

        //echo '<pre>'; print_r($data);
        //die;

        $list = $this->processTweetTimeline($tweet_data, $data);

        //echo '<pre>'; print_r($list);
        //die;

        return $list;
    }

    public function userLookup($username) {
        $args = [];
        $args['expansions'] = 'pinned_tweet_id';
        $args['user.fields'] = 'created_at,description,entities,location,id,profile_image_url,protected,public_metrics,url';

        $url = '/2/users/by/username/' . urlencode($username);
        $url .= '?' . http_build_query($args);
        $response = $this->get($url);

        //echo '<pre>'; print_r($response); echo '</pre>'; die;
        return $response;
    }

    public function userTimeline($username, $pagination_token = '') {
        $response = $this->userLookup($username);
        if(!isset($response->data)) {
            throw new Exception('The page or user you are attempting to access does not appear to be available.', '/home');
        }

        $url = '/2/users/' . $response->data->id . '/tweets';
        $url .= '?' . http_build_query($this->timeline_args);
        $url .= '&' . 'exclude=replies';
        if($pagination_token) {
            $url .= '&' . http_build_query(['pagination_token' => $pagination_token]);
        }

        $data = $this->get($url);
        
        //echo '<pre>'; print_r($data);

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($list);
        //die;

        return $list;
    }
}
