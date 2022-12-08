<?php

namespace app\controllers;

use app\models\Connection;
use app\models\Profile;
use app\models\UserSetting;

use mavoc\core\REST;

class TwitterController {
    public function base64URLEncode($input) {
        $output = rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
        return $output;
    }

    public function redirect($req, $res) {
		$val = $req->val('query', [
			'state' => ['required'],        
			'code' => ['required'], 
		], '/home');        

        // Need to check the state against session data.
        $state = $req->query['state'];  
        if(ao()->session->data['twitter_state'] != $state) { 
            $res->error('There was a problem with the communication for the login. Please try again and if the problem happens again, please contact support.', '/home');
        }

        $verifier = ao()->session->data['twitter_verifier'];

        $authentication = base64_encode(ao()->env('TWITTER_CLIENT_ID') . ':' . ao()->env('TWITTER_CLIENT_SECRET'));
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $authentication,
        ];
        $rest = new REST($headers);    
        // Make a curl call.   
        $url = ao()->env('TWITTER_URL_TOKEN');
        $post = [
            'grant_type' => 'authorization_code',
            'client_id' => ao()->env('TWITTER_CLIENT_ID'),
            'redirect_uri' => ao()->env('TWITTER_URL_REDIRECT'),
            'code' => $val['code'],  
            'code_verifier' => $verifier,
            //'code_verifier' => 'challenge',
        ];
        // For some reason, it doesn't work when you passed in as an array so converting it to query args.
        // (I saw someone else on the Twitter forums describe this same issue.)
        // I don't have time right now to see what the difference is with the way curl makes the call.
        // I'm guessing it has something to do with explicitly calling the Content-Type header above.
        $values = http_build_query($post);
        $access = $rest->post($url, $values, [], true);
        //$access = $rest->post($url, $post, [], true);
        if(!isset($access['access_token'])) {
            $res->error('There was a problem completing the login. Please try again and if the problem happens again, please contact support.', '/home');
        }

        // Get user_id
        $rest = new REST($access['access_token']);
        $response = $rest->get(ao()->env('TWITTER_URL') . '/2/users/me?user.fields=profile_image_url,verified');
        if(!isset($response->data->id)) {
            $res->error('There was a problem accessing user info. Please try again and if the problem happens again, please contact support.', '/home');
        }

        // Get the profile by user
        $profile = Profile::by(['user_id' => $req->user_id, 'twitter_id' => $response->data->id]);
        if(!$profile) {
            $args = [];
            $args['user_id'] = $req->user_id;
            $args['twitter_id'] = $response->data->id;
            $args['twitter_name'] = $response->data->name;
            $args['twitter_username'] = $response->data->username;
            $args['twitter_profile_image_url'] = $response->data->profile_image_url;
            $args['twitter_verified'] = $response->data->verified ? 1 : 0;
            $args['connection_id'] = 0;
            $profile = Profile::create($args);
        } else {
            $profile->data['twitter_name'] = $response->data->name;
            $profile->data['twitter_username'] = $response->data->username;
            $profile->save();
        }

        // Update the $user_setting if the profile is set to 0
        $user_setting = UserSetting::by('user_id', $req->user_id);
        /*
        echo '$req->user_id:: ' . $req->user_id;
        echo '<br>';
        echo 'user_setting: ';
        print_r($user_setting);
         */
        if($user_setting->data['profile_id'] == 0) {
            $user_setting->data['profile_id'] = $profile->id;
            $user_setting->save();
        }

        // Create or Update the connection
        $connection = Connection::find($profile->data['connection_id']);
        if($connection) {
            $connection->data['user_id'] = $req->user_id;
            $connection->data['twitter_id'] = $response->data->id;
            $connection->data['data'] = $access;
            $connection->data['encrypted'] = 0;
            $connection->save();
        } else {
            $args = [];
            $args['user_id'] = $req->user_id;
            $args['twitter_id'] = $response->data->id;
            $args['data'] = $access;
            $args['encrypted'] = 0;
            $connection = Connection::create($args);
        }

		$profile->data['connection_id'] = $connection->id;
		$profile->save();

        $res->redirect('/home');
    }

    public function start($req, $res) {
        $session_id = ao()->session->id;
        if(!$session_id) {
            // If for some reason the session_id is not returned, use random values.
            // Based on: https://developer.okta.com/blog/2018/07/09/five-minute-php-app-auth
            // https://www.php.net/random_bytes
            $session_id = bin2hex(random_bytes(5));
        }   
        $state = hash('sha256', $session_id);
        ao()->session->data['twitter_state'] = $state;

        $verifier = $this->base64URLEncode(random_bytes(32));
        ao()->session->data['twitter_verifier'] = $verifier;

        // https://developer.salesforce.com/forums/?id=906F0000000D6kjIAC
        //$challenge = $this->base64URLEncode(hash('sha256', $verifier));
        //$challenge = hash('sha256', $verifier);
        $challenge = $this->base64URLEncode(pack('H*', hash('sha256', $verifier)));

        $scope = 'offline.access';
        $scope .= ' bookmark.read';
        $scope .= ' bookmark.write';
        $scope .= ' follows.read';
        $scope .= ' follows.write';
        $scope .= ' like.read';
        $scope .= ' like.write';
        $scope .= ' list.read';
        $scope .= ' list.write';
        $scope .= ' tweet.read';
        $scope .= ' tweet.write';
        $scope .= ' users.read';



        $args = [];
        $args['response_type'] = 'code';
        $args['client_id'] = ao()->env('TWITTER_CLIENT_ID');
        $args['redirect_uri'] = ao()->env('TWITTER_URL_REDIRECT');
        $args['scope'] = $scope;
        $args['state'] = $state;
        $args['code_challenge'] = $challenge;
        $args['code_challenge_method'] = 'S256';

        $url = ao()->env('TWITTER_URL_AUTHORIZE');
        $url .= '?' . http_build_query($args);

        $res->redirect($url);
    }

}
