<?php
/**
 * Filename tweets.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Abraham\TwitterOAuth\TwitterOAuth;

$title       = get_field( 'title' );
$screen_name = get_field( 'screen_name' );

$consumer_key    = get_theme_mod( '_toibox_twitter_api_consumer_key' );
$consumer_secret = get_theme_mod( '_toibox_twitter_api_consumer_secret' );

// @TODO make this a REST call so it doesn't block page load
$tweets = get_transient( "tweets-$screen_name" );
if ( false === $tweets ) {
  $connection = new TwitterOAuth( $consumer_key, $consumer_secret );
  $response   = $connection->get(
    'statuses/user_timeline',
    [
      'screen_name' => $screen_name,
      'count'       => 4,
      'include_rts' => true,
    ]
  );
  if ( $response->error ) {
    $tweets = [];
  } else {
    $tweets = $response;
  }

  set_transient( "tweets-$screen_name", $tweets, HOUR_IN_SECONDS );
}

?>
<div class="container">
  <div class="row">
    <div class="col">
      <h2><?php echo $title; ?></h2>
    </div>
  </div>
  <div class="row">
    <?php foreach ( $tweets as $tweet ) : ?>
      <div class="col">
        <div class="article">
          <?php //TODO link tweet entities ?>
          <?php echo $tweet->text ?: ''; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
