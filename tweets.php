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

function print_tweet($tweet) {
    $tweeted = $tweet->created_at;
    $time = date('g:ia', strtotime($tweeted));
    $date = date('F d, Y', strtotime($tweeted));
    $text = $tweet->text;
    $name = $tweet->user->name;
    $handle = $tweet->user->screen_name;
    $id = $tweet->id_str;
    if (!empty($tweet->entities->urls)) {
        foreach ($tweet->entities->urls as $url) {
            $find = $url->url;
            $expanded = $url->expanded_url;
            $replace = '<a href="'.$expanded.'">'.$expanded.'</a>';
            $text = str_replace($find, $replace, $text);
        }
    }
    if(!empty($tweet->entities->hashtags)) {
        foreach ($tweet->entities->hashtags as $hashtag) {
            $find = '#'.$hashtag->text;
            $replace = '<a href="http://twitter.com/#!/search/%23'.$hashtag->text.'">'.$find.'</a>';
            $text = str_replace($find, $replace, $text);
        }
    }
    if (!empty($tweet->entities->user_mentions)) {
        foreach ($tweet->entities->user_mentions as $user_mention) {
            $last = $user_mention->screen_name;
            $find = "@".$user_mention->screen_name;
            $replace = '<a href="http://twitter.com/'.$user_mention->screen_name.'">'.$find.'</a>';
            $text = str_ireplace($find, $replace, $text);
        }
    }
    if (!empty($tweet->entities->media)) {
        foreach ($tweet->entities->media as $media) {
            $find = $media->url;
            $expanded = $media->expanded_url;
            $replace = '<a href="'.$expanded.'">'.$expanded.'</a>';
            $text = str_replace($find, $replace, $text);
        }
    }
    print "<div class='bubble tweet'>" . $text . "</div>";
    if (isset($last)) {
        print "<a class='profile' title='" . $last . "' href='http://www.twitter.com/" . $last . "'>@" . $last . "</a>";
    }
    else {
        print "<a class='profile' title='" . $name . "' href='http://www.twitter.com/" . $handle . "'>@" . $handle . "</a>";
    }
    print " <a class='date' href='/index.php?id=" . $id . "'>" . " at " . $time . " on " . $date . "</a>";
}
?>
<div id="tweets">
    <h2>Tweets</h2>
<?php
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$tweet_id = $_GET['id'];
if ($tweet_id == '') {
    $tweets = $twitter->get('statuses/user_timeline', array('screen_name' => TWITTER_USERNAME, 'include_entities' => 1), "tweets.json");
    foreach($tweets as $tweet){
        print_tweet($tweet);
    }
}
else {
    $tweet = $twitter->get('statuses/show', array('id' => $tweet_id, 'include_entities' => 1), null);
    print_tweet($tweet);
}
?>
</div>