<?php
/**
 * Filename tweets.php
 *
 * @package clean50
 * @author  Peter Toi <peter@petertoi.com>
 */

use Abraham\TwitterOAuth\TwitterOAuth;
use Twitter\Text\Autolink;

date_default_timezone_set( 'America/Toronto' );

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
      'count'       => 3,
      'include_rts' => true,
    ]
  );
  if ( isset( $response->error ) ) {
    $tweets = [];
  } else {
    $tweets = $response;
  }

  set_transient( "tweets-$screen_name", $tweets, 6 * HOUR_IN_SECONDS );
}

?>
<?php if ( ! empty( $tweets ) ) : ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <h2><?php echo $title; ?></h2>
      </div>
      <div class="col-auto text-right">
        <?php if ( ! empty( $screen_name ) ) : ?>
          <?php printf( '<a class="article-read-more" href="https://twitter.com/%s">%s</a>', esc_attr( $screen_name ), _x( 'View on Twitter', '', '' ) ); ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <?php foreach ( $tweets as $tweet ) : ?>
        <div class="col-12 col-md-4 mb-4 mb-md-0">
          <article class="tweet">
            <?php if ( ! empty( $tweet->text ) ) : ?>
              <div class="tweet-text">
                <?php echo Autolink::create()->setNoFollow( true )->autoLink( $tweet->text ); ?>
              </div>
              <div class="tweet-datetime">
                <?php
                $created_at = strtotime( $tweet->created_at );
                printf(
                  '<time datetime="%s"><a class="stretched-link" href="https://twitter.com/statuses/%s">%s</a></time>',
                  $tweet->created_at,
                  $tweet->id_str,
                  date( 'g:i A - d M Y', $created_at )
                );
                ?>
              </div>
            <?php endif; ?>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
