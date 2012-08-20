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

session_start();
require_once('lib/Twitter.php');
require_once('config.php');

if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: ./logout.php');
}

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
$_SESSION['access_token'] = $access_token;

unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

if (200 == $twitter->http_code) {
    $_SESSION['oauth_status'] = 'verified';
    header('Location: ./index.php');
}
else {
    header('Location: ./logout.php');
}
?>