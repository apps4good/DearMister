<?php
// ##########################################################################################
//
// Copyright (c) 2012, Apps4Good. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without modification, are
// permitted provided that the following conditions are met:
//
// 1) Redistributions of source code must retain the above copyright notice, this list of
//    conditions and the following disclaimer.
// 2) Redistributions in binary form must reproduce the above copyright notice, this list
//    of conditions and the following disclaimer in the documentation
//    and/or other materials provided with the distribution.
// 3) Neither the name of the Apps4Good nor the names of its contributors may be used to
//    endorse or promote products derived from this software without specific prior written
//    permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
// EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
// OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
// SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
// OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
// TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
// ##########################################################################################

require_once('config.php');
require_once('lib/OAuth.php');
require_once('lib/Twitter.php');

function prettyPrint($json) {
    $result = '';
    $level = 0;
    $prev_char = '';
    $in_quotes = false;
    $ends_line_level = NULL;
    $json_length = strlen($json);
    for ($i = 0; $i < $json_length; $i++) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if($ends_line_level !== NULL) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if($char === '"' && $prev_char != '\\') {
            $in_quotes = !$in_quotes;
        }
        else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;
                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;
                case ':':
                    $post = " ";
                    break;
                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        }
        if ($new_line_level !== NULL) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
        $prev_char = $char;
    }
    return $result;
}

$json = array();
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$tweets = $twitter->get('statuses/user_timeline', array('screen_name' => TWITTER_USERNAME, 'include_entities' => 1), "tweets.json");

foreach($tweets as $tweet){
    $expanded = $tweet->text;
    $sender = null;
    if (!empty($tweet->entities->urls)) {
        foreach ($tweet->entities->urls as $url) {
            $find =  $url->url;
            $replace = $url->expanded_url;
            $expanded = str_replace($find, $replace, $expanded);
        }
    }
    if (!empty($tweet->entities->user_mentions)) {
        foreach ($tweet->entities->user_mentions as $user_mention) {
            $sender = $user_mention->screen_name;
        }
    }
    if (!empty($tweet->entities->media)) {
        foreach ($tweet->entities->media as $media) {
            $find =  $media->url;
            $replace = $media->expanded_url;
            $expanded = str_replace($find, $replace, $expanded);
        }
    }
    array_push($json, array(
        'id' => $tweet->id_str,
        'name' => $tweet->user->name,
        'handle' => $tweet->user->screen_name,
        'sender' => $sender,
        'date' => $tweet->created_at,
        'text' => $tweet->text,
        'expanded' => $expanded,
        'retweets' => $tweet->retweet_count));
}
header("Content-Type: text/javascript; charset=UTF-8");
echo prettyPrint(json_encode($json));
die();

?>