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
<div id="update">
    <form method='post' action='index.php' name="form1">
        <input type="hidden" id="tweet" name="tweet"/>
        <div class='bubble'>
            <div id="prefix"><?php echo TWEET_PREFIX ?></div>
            <input type="text" name="input" id="input" placeholder="<?php echo TWEET_PLACEHOLDER; ?>"/>
            <div id="suffix"></div>
            <div id='count'></div>
        </div>
<?php
    if (empty($_SESSION['access_token'])) {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $twitter->getRequestToken(OAUTH_CALLBACK);
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $url = $twitter->getAuthorizeURL($request_token['oauth_token']);
        echo "<input id='handle' type='hidden' value='' />";
        echo "<br/><a id='signin' class='button' href='" . $url . "' title='Signin via Twitter'>Signin</a>";
    }
    else {
        $access_token = $_SESSION['access_token'];
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user = $twitter->get('account/verify_credentials');
        $name = $user->name;
        $handle = $user->screen_name;
        $thumb = $user->profile_image_url;
        echo "<input id='handle' type='hidden' value='" . $handle. "' />";
        echo "<br/><a class='button' href='#' onclick='document.form1.submit();' id='submitSend'>Send</a>";
        echo "<a id='signout' title='Signout @" . $handle. "' href='logout.php'>Signout</a>";
        if (isset($_POST['tweet'])) {
            $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_KEY, ACCESS_SECRET);
            $result = $twitter->post('statuses/update', array('status' => $_POST['tweet']));
            if (isset($result->error)) {
                echo "<div id='error'>" . $result->error . "</div>";
            }
            else {
                echo "<div id='sent'>" . $_POST['tweet'] . "</div>";
            }
        }
    }
?>
    </form>
</div>
