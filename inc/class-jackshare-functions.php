<?php

class JackShareFunctions {

    /**
     * Constructor
     */
    public function __construct()
    {
       

        //Create admin menu for settings page
        add_action( 'wp_head', array( &$this, 'jackshare_enable_meta_tags' ), 0 );

        add_filter('the_content', array( &$this, 'jackshare_add_content' ));

    }



    //Add Facebook Meta Tags if needeed
    function jackshare_enable_meta_tags () {

        $jackshare_options = get_option( 'jackshare_settings' );

        if ( have_posts() ) {

            while ( have_posts() ) {

                the_post();

                $cleaned_excerpt = wp_filter_nohtml_kses(get_the_excerpt());
                $cleaned_content = wp_filter_nohtml_kses(get_the_content());
            }

        } 

        ob_start();


        if ( isset($jackshare_options['jackshare_facebook_meta']) == true ) : ?>
            
            <!-- JACKSHARE ENABLE FACEBOOK OPEN GRAPH TAGS -->
            <meta property="og:url"           content="<?php the_permalink(); ?>" />
            <meta property="og:type"          content="website" />
            <meta property="og:title"         content="<?php the_title(); ?>" />
            <meta property="og:description"   content="<?php echo $desc = has_excerpt() ? $cleaned_excerpt : $cleaned_content; ?>" />
            <meta property="og:image"         content="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" />

        <?php

        echo ob_get_clean();

        endif;

    }

    //Add Social Share Buttons

    function jackshare_add_content ($content) {

        $jackshare_options = get_option( 'jackshare_settings' );

        global $post;

        $twitter_via = $jackshare_options['jackshare_twitter_via'];


        //Current position selected by user
        $position = $jackshare_options['jackshare_position'];

        // Display Names of social media
        $display_names = $jackshare_options['jackshare_enable_social_names'];

        // Display the selected theme and make them lowercase to use it as css class


        // User selected theme

        if ( $jackshare_options['jackshare_buttons_style'] == 'rounded' ) {
            $selected_theme = 'rounded';
        }elseif( $jackshare_options['jackshare_buttons_style'] == 'radius' ){
            $selected_theme = 'radius';
        }elseif( $jackshare_options['jackshare_buttons_style'] == 'square' ){
            $selected_theme = 'square';
        }


        // User selected color scheme

        if ( $jackshare_options['jackshare_color_template'] == 'transparent' ) {
            $color = 'transparent';
        }elseif ( $jackshare_options['jackshare_color_template'] == 'colored' ){
            $color = 'colored';
        }

        // Social media enabled from dashboard
        $enable_fb            = isset($jackshare_options['jackshare_facebook']) ? $jackshare_options['jackshare_facebook'] : 0;
        $enable_messenger     = isset($jackshare_options['jackshare_messenger']) ? $jackshare_options['jackshare_messenger'] : 0;
        $enable_twitter       = isset($jackshare_options['jackshare_twitter']) ? $jackshare_options['jackshare_twitter'] : 0;
        $enable_pinterest     = isset($jackshare_options['jackshare_pinterest']) ? $jackshare_options['jackshare_pinterest'] : 0;
        $enable_linkedin      = isset($jackshare_options['jackshare_linkedin']) ? $jackshare_options['jackshare_linkedin'] : 0 ;
        $enable_viber         = isset($jackshare_options['jackshare_viber']) ? $jackshare_options['jackshare_viber'] : 0;
        $enable_whatsapp      = isset($jackshare_options['jackshare_whatsapp']) ? $jackshare_options['jackshare_whatsapp'] : 0;

        // Get the current post's URL that will be shared
        $jackshare_post_url = urlencode( esc_url( get_permalink($post->ID) ) );

        // Get the post's title
        $jshare_post_title = urlencode( esc_attr($post->post_title) );

        // Get the post featured image
        $jackshareThumbnail   = wp_get_attachment_url( get_post_thumbnail_id() );

        $jackshare_desc       = esc_attr( wp_trim_words( get_the_content(), 40, '' ), array() );

        // Share this message

        $share_counter    = isset($jackshare_options['jackshare_social_share_counter']) ? $jackshare_options['jackshare_social_share_counter'] : 0 ;
        $reaction_counter = isset($jackshare_options['jackshare_display_reaction_count']) ? $jackshare_options['jackshare_display_reaction_count'] : 0;
        $comment_counter  = isset($jackshare_options['jackshare_display_comment_counter']) ? $jackshare_options['jackshare_display_comment_counter'] : 0;


        // Social media names
        $facebook             = esc_attr__('Facebook', 'jackshare');
        $messenger            = esc_attr__('Send a message', 'jackshare');
        $twitter              = esc_attr__('Twitter', 'jackshare');
        $pinterest            = esc_attr__('Pin it!', 'jackshare');
        $linkedin             = esc_attr__('Linkedin', 'jackshare');
        $viber                = esc_attr__('Viber', 'jackshare');
        $whatsapp             = esc_attr__('WhatsApp','jackshare');

        // Facebook App ID & App Secret
        $fb_app_id     = $jackshare_options['add_fb_app_id'];
        $fb_app_secret = $jackshare_options['add_fb_app_secret'];

        // Alt titles
        $fb_alt_title         = esc_attr__( 'Share this on Facebook', 'jackshare' );
        $messenger_alt_title  = esc_attr__( 'Send a message', 'jackshare' );
        $twitter_alt_title    = esc_attr__( 'Share this on Twitter', 'jackshare' );
        $pinterest_alt_title  = esc_attr__( 'Pin this!', 'jackshare' );
        $linkedin_alt_title   = esc_attr__( 'Share this on Linkedin', 'jackshare' );
        $viber_alt_title      = esc_attr__( 'Send this on Viber', 'jackshare' );
        $whatsapp_alt_title   = esc_attr__( 'Send this on WhatsApp', 'jackshare' );

        // Create the share links for Facebook, Twitter, Pinterest and Linkedin
        $jackshare_pinterest = "";
        $jackshare_fb           = sprintf( 'https://www.facebook.com/dialog/share?app_id=%2$s&amp;display=popup&amp;href=%1$s&amp;redirect_uri=%1$s', $jackshare_post_url, $fb_app_id );
        $jackshare_messenger    = sprintf( 'http://www.facebook.com/dialog/send?app_id=%1$s&amp;link=%2$s&amp;redirect_uri=%2$s&amp;display=popup', $fb_app_id, $jackshare_post_url );
        $jackshare_twitter      = sprintf( 'https://twitter.com/intent/tweet?text=%2$s&url=%1$s', $jackshare_post_url, $jshare_post_title );
        $jackshare_twitter_via  = sprintf( 'https://twitter.com/intent/tweet?text=%1$s&url=%2$s&via=%3$s', $jshare_post_title, $jackshare_post_url, $twitter_via );
        $jackshare_pinterest    = sprintf( 'https://pinterest.com/pin/create/button/?url=%1$s&media=%2$s&description=%3$s', $jackshare_post_url, $jackshareThumbnail, $jshare_post_title );
        $jackshare_linkedin     = sprintf( 'https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s&ro=false&summary=%3$s', $jackshare_post_url, $jshare_post_title, $jackshare_desc );
        $jackshare_viber     = sprintf( 'viber://forward?text=%1$s', $jshare_post_title );
        $jackshare_whatsapp  = sprintf( 'whatsapp://send?text=%1$s', $jshare_post_title );

        $url          = esc_url( get_permalink($post->ID) );
        $access_token = $fb_app_id . '|' . $fb_app_secret;
        $check_url    = 'https://graph.facebook.com/v8.0/?id=' . urlencode( $url ) . '&fields=engagement&access_token=' . $access_token;
        $cache_key    = md5( 'remote_request|' . $check_url );
        $request      = get_transient( $cache_key );


        if ( false === $request ) {

            $request = wp_remote_get( $check_url );
           

            if ( is_wp_error( $request ) ) {
                // If Cache fails for a time save in cache for 15 minutes
                set_transient( $cache_key, $request, MINUTE_IN_SECONDS * 15 );
                return false;
            }

            // Success, cache for 2 hours
            set_transient( $cache_key, $request, MINUTE_IN_SECONDS * $jackshare_options['jackshare_fb_cache_req'] ); // Consider if we need this to be a field in settings also.

        }

        if ( is_wp_error( $request ) ) {
            return false;
        }


        //Get the response
        $response = wp_remote_retrieve_body( $request );
        $share_data = json_decode( $response );
        $share_number = 0;
        $reaction_number = 0;
        $comment_count = 0;

        if ( !empty( $share_data ) && !isset($share_data->error) ) {

            $share_number        = intval($share_data->engagement->share_count);
            $reaction_number     = intval($share_data->engagement->reaction_count);       
            $comment_count       = intval($share_data->engagement->comment_count);
            
        }


        // Sum together reactions and shares to get a beatiful number
        if ( ($share_counter == true) && ($reaction_counter == true) && ($comment_counter == false) ) {

            $share_number = $share_number + $reaction_number;
              
        }elseif ( ($share_counter == true) && ($reaction_counter == true) && ($comment_counter == true) ) {

            $share_number = $share_number + $reaction_number + $comment_count;

        }elseif ( ($share_counter == true) && ($reaction_counter == false) && ($comment_counter == false) ){

            $share_number;
            
        }elseif ( ($share_counter == false) && ($reaction_counter == true) && ($comment_counter == false) ) {

            $share_number = $reaction_number;
           
        }elseif ( ($share_counter == false) && ($reaction_counter == false) && ($comment_counter == true) ) {

            $share_number = $comment_count;
            
        }else {

            $share_number = 0;
            
        }

        if ( ($share_counter == true && $share_number > 1 ) || ($reaction_counter == true && $share_number > 1 ) || ($comment_counter == true && $share_number > 1 ) ) {
            $jackshare_share_msg  = esc_attr__('Shares', 'jackshare');
        }elseif(($share_counter == true && $share_number == 1) || ($reaction_counter == true && $share_number == 1 ) || ($comment_counter == true && $share_number == 1)) {
            $jackshare_share_msg  = esc_attr__('Share', 'jackshare');
        }else {
            $jackshare_share_msg  = esc_attr__('Share this', 'jackshare');
        }

        $share_number_element = "";
        if ( $share_number > 0 ){
            $share_number_element = '<span class="share-number">'. $share_number .'</span>';
        }

        if ( ( is_singular() ) && ( is_main_query() ) ) {


            // Give title of sharing area
            $jackshare_output  = '<div class="jackshare-container"><div class="jackshare-message">'.( $share_counter || $reaction_counter || $comment_counter ? ''. $share_number_element .'': '').'<p>' . $jackshare_share_msg . '</p></div>';

            // Wrap the buttons
            $jackshare_output .= '<div class="jackshare-sharing ' . $selected_theme . ' ' . $color . '"><ul>';

                //Add the links inside the wrapper
                if( $enable_fb == true ) {
                   $jackshare_output .= '<li><a class="jackshare-link facebook" href="' . $jackshare_fb . '" title="'. $fb_alt_title .'"><i class="jshare-font-facebook"></i>'. ($display_names == true ? '<span class="j_title">'. $facebook .'</span>': '').'</a></li>';
                }
                if ( $enable_messenger == true ) {
                     $jackshare_output .= '<li><a class="jackshare-link messenger" href="' . $jackshare_messenger . '" title="'. $messenger_alt_title .'"><i class="jshare-font-messenger"></i>'. ($display_names == true ? '<span class="j_title">'. $messenger .'</span>': '').'</a></li>';
                }
                if( $enable_twitter == true ){
                    $jackshare_output .= '<li><a class="jackshare-link twitter" href="' . ($jackshare_options['jackshare_twitter_via'] != "" ? ''. $jackshare_twitter_via .'' : ''. $jackshare_twitter .'' ) . '" title="'. $twitter_alt_title .'"><i class="jshare-font-twitter"></i>'. ($display_names == true ? '<span class="j_title">'. $twitter .'</span>': '').'</a></li>';
                }
                if( $enable_pinterest == true ){
                    $jackshare_output .= '<li><a class="jackshare-link pinterest" href="' . $jackshare_pinterest . '" title="'. $pinterest_alt_title .'"><i class="jshare-font-pinterest"></i>'. ($display_names == true ? '<span class="j_title">'. $pinterest .'</span>': '').'</a></li>';
                }
                if( $enable_linkedin == true ){
                    $jackshare_output .= '<li><a class="jackshare-link linkedin" href="' . $jackshare_linkedin . '" title="'. $linkedin_alt_title .'"><i class="jshare-font-linkedin-bold"></i>'. ($display_names == true ? '<span class="j_title">'. $linkedin .'</span>': '').'</a></li>';
                }
                if( $enable_viber == true ){
                    $jackshare_output .= '<li><a id="viber_share" class="jackshare-link viber" href="' . $jackshare_viber . ' ' . $jackshare_post_url . '" title="'. $viber_alt_title .'"><i class="jshare-font-viber"></i>'. ($display_names == true ? '<span class="j_title">'. $viber .'</span>': '').'</a></li>';
                }
                if( $enable_whatsapp == true ){
                    $jackshare_output .= '<li><a data-action="share/whatsapp/share" class="jackshare-link whatsapp" href="' . $jackshare_whatsapp . ' ' . $jackshare_post_url . '" title="'. $whatsapp_alt_title .'"><i class="jshare-font-whatsapp"></i>'. ($display_names == true ? '<span class="j_title">'. $whatsapp .'</span>': '').'</a></li>';
                }

            //Close the wrapper
            $jackshare_output .= '</ul></div></div>';

        }

        if ( ($jackshare_options['jackshare_position'] == 'after_content') && (is_singular()) && (!is_front_page()) ) {

            return $content . $jackshare_output;

        }elseif ( ($jackshare_options['jackshare_position'] == 'before_content') && (is_singular()) && (!is_front_page()) ) {

            return $jackshare_output . $content;

        }else {
            return $content;
        }

    }


}


?>