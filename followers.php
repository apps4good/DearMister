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
?>
<div id="followers">
    <a class="followers" title="Followers of <?php echo TWITTER_USERNAME ?>" href="http://twitter.com/<?php echo TWITTER_USERNAME ?>/followers">Followers</a>
<?php
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$followers = $twitter->get("statuses/followers",  array('screen_name' => TWITTER_USERNAME), "followers.json");
foreach($followers as $follower){
    $thumb = $follower->profile_image_url;
    $handle = $follower->screen_name;
    $description = $follower->description;
    $name = $follower->name;
    print "<a class='follower' title='" . $name . "' href='http://www.twitter.com/" . $handle . "'>" . "<img src='" . $thumb . "' /></a>";
}
?>
</div>