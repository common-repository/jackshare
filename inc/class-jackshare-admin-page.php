<?php

class JackShareAdminPage {

 	/**
     * Constructor
     */
    public function __construct()
    {

        //Create admin menu for settings page
        add_action( 'admin_menu', array( &$this, 'jackshare_options_admin_menu' ) );

        //Set up admin options in settings page
        add_action( 'admin_init', array( &$this, 'jackshare_options_init' ) );
    }

 	/**
     * This function adds the menu item in admin menu
     */
    function jackshare_options_admin_menu() {
        add_options_page(
            'Jackshare',
            'Jackshare',
            'manage_options',
            'jackshare',
            array( &$this, 'jackshare_options_page_init' )
        );
    }

     /**
     * This function prints the options page
     */
    function jackshare_options_page_init() {

        $tabs = array(
            'basic'=> array(
                'slug'  => 'basic',
                'title' => __( 'Basic', 'jackshare' )
            ),
            'facebook_options'=> array(
                'slug'  => 'facebook',
                'title' => __( 'Facebook settings', 'jackshare' )
            ),
         	'twitter_options'=> array(
                'slug'  => 'twitter',
                'title' => __( 'Twitter settings', 'jackshare' )
            ),
        );

        $tabs = apply_filters( 'jackshare_filter_settings_tabs', $tabs );

        ?>

        <form action='options.php' method='post'>

            <div class="wrap">

                <h1><?php echo __( 'Jackshare Options', 'jackshare' ); ?>
                	<code style="background: #00a0d2;color: #fff;"><?php echo JACKSHARE_PLUGIN_VERSION; ?></code>
                </h1>

                <?php
                foreach ( $tabs as $current_tab ) {
                    do_action( "jackshare_action_settings_{$current_tab['slug']}_section" );
                    do_action( "jackshare_action_settings_{$current_tab['slug']}_fields" );
                }

                settings_fields( 'jackshare_options_page' );
                do_settings_sections( 'jackshare_options_page' );
                submit_button();

                ?>
            </div>

        </form>

        <?php

    }

 	/**
     * This function initializes the options page
     */
    function jackshare_options_init() {
        register_setting( 'jackshare_options_page', 'jackshare_settings' );

        //Options actions
        add_action( 'jackshare_action_settings_basic_section', array( $this, 'jackshare_options_page_section_basic' ), 99 );
        add_action( 'jackshare_action_settings_facebook_section', array( $this, 'jackshare_options_page_section_facebook' ), 99 );
        add_action( 'jackshare_action_settings_twitter_section', array( $this, 'jackshare_options_page_section_twitter' ), 99 );
    }

    /**
     * This function prints the basic options page
     */
    function jackshare_basic_options_section_callback() {
        echo __( 'This section contains basic options for Jackshare Social Sharing plugin', 'jackshare' );
    }

 	/**
     * This function prints the Facebook options page
     */
    function jackshare_facebook_options_section_callback() {
        echo __( 'This section contains options for Facebook Sharing.', 'jackshare' );
    }

    /**
     * This function prints the Twitter options page
     */
    function jackshare_twitter_options_section_callback() {
        echo __( 'This section contains options for Twitter.', 'jackshare' );
    }

    function jackshare_options_page_section_basic() {

        $settings_section = array(
            'jackshareBasicOptionsSection',
            __( 'Basic', 'jackshare' ),
            array(&$this, 'jackshare_basic_options_section_callback' ),
            'jackshare_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section );

     	add_settings_field(
            'jackshare_position',
            __( 'Positioning', 'jackshare' ),
            array(&$this, 'jackshare_position_buttons' ),
            'jackshare_options_page',
            'jackshareBasicOptionsSection'
        );

        add_settings_field(
            'jackshare_buttons_style',
            __( 'Style of buttons', 'jackshare' ),
            array(&$this, 'jackshare_style_buttons' ),
            'jackshare_options_page',
            'jackshareBasicOptionsSection'
        );

 		add_settings_field(
            'jackshare_color_template',
            __( 'Color Template', 'jackshare' ),
            array(&$this, 'jackshare_color_template_buttons' ),
            'jackshare_options_page',
            'jackshareBasicOptionsSection'
        );

        add_settings_field(
            'jackshare_enable_social_names',
            __( 'Display names', 'jackshare' ),
            array(&$this, 'jackshare_display_social_names' ),
            'jackshare_options_page',
            'jackshareBasicOptionsSection'
        );

     	add_settings_field(
            'jackshare_social_media_selection',
            __( 'Display Social media', 'jackshare' ),
            array(&$this, 'jackshare_social_media_selected' ),
            'jackshare_options_page',
            'jackshareBasicOptionsSection'
        );

    }

    /* Positioning of buttons */
    function jackshare_position_buttons() {

        $options = get_option( 'jackshare_settings' );
        $options['jackshare_position'] = ( isset($options['jackshare_position']) ? $options['jackshare_position'] : 'before_content' );
        ?>
        <select id='jackshare_position' name='jackshare_settings[jackshare_position]'>
            <option value='before_content' <?php selected($options['jackshare_position'], 'before_content' ); ?>><?php _e( 'Before Content', 'jackshare' ); ?></option>
            <option value='after_content' <?php selected($options['jackshare_position'], 'after_content' ); ?>><?php _e( 'After Content', 'jackshare' ); ?></option>
        </select>

	<?php
    }

    /* Buttons Style */
    function jackshare_style_buttons() {

        $options = get_option( 'jackshare_settings' );
        $options['jackshare_buttons_style'] = ( isset($options['jackshare_buttons_style']) ? $options['jackshare_buttons_style'] : 'rounded' );
        ?>
        <select id='jackshare_buttons_style' name='jackshare_settings[jackshare_buttons_style]'>
            <option value='rounded' <?php selected($options['jackshare_buttons_style'], 'rounded' ); ?>><?php esc_attr_e( 'Rounded', 'jackshare' ); ?></option>
            <option value='radius' <?php selected($options['jackshare_buttons_style'], 'radius' ); ?>><?php esc_attr_e( 'Radius', 'jackshare' ); ?></option>
            <option value='square' <?php selected($options['jackshare_buttons_style'], 'square' ); ?>><?php esc_attr_e( 'Square', 'jackshare' ); ?></option>
        </select>

	<?php
    }


    /* Coloring Template of buttons */
    function jackshare_color_template_buttons() {

        $options = get_option( 'jackshare_settings' );
        $options['jackshare_color_template'] = ( isset($options['jackshare_color_template']) ? $options['jackshare_color_template'] : 'transparent' );
        ?>
        <select id='jackshare_color_template' name='jackshare_settings[jackshare_color_template]'>
            <option value='transparent' <?php selected($options['jackshare_color_template'], 'transparent' ); ?>><?php esc_attr_e( 'Transparent', 'jackshare' ); ?></option>
            <option value='colored' <?php selected($options['jackshare_color_template'], 'colored' ); ?>><?php esc_attr_e( 'Colored', 'jackshare' ); ?></option>
        </select>

	<?php
    }


 	/* Display Social Names */
    function jackshare_display_social_names() {

        $options = get_option( 'jackshare_settings' );
        $options['jackshare_enable_social_names'] = ( isset($options['jackshare_enable_social_names']) ? $options['jackshare_enable_social_names'] : 0 );
        ?>
        <input id="jackshare_enable_social_names" type="checkbox" name="jackshare_settings[jackshare_enable_social_names]" value="1" <?php checked('1', $options['jackshare_enable_social_names']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_enable_social_names]"><?php esc_attr_e('Enable Social media names on buttons', 'jackshare'); ?></label>

	<?php
    }

  	/* Display Social Media */
    function jackshare_social_media_selected() {

        $options = get_option( 'jackshare_settings' );
		$options['jackshare_facebook']  = ( isset($options['jackshare_facebook']) ? $options['jackshare_facebook'] : 0 );
		$options['jackshare_messenger'] = ( isset($options['jackshare_messenger']) ? $options['jackshare_messenger'] : 0 );
		$options['jackshare_twitter']   = ( isset($options['jackshare_twitter']) ? $options['jackshare_twitter'] : 0 );
		$options['jackshare_pinterest'] = ( isset($options['jackshare_pinterest']) ? $options['jackshare_pinterest'] : 0 );
		$options['jackshare_linkedin']  = ( isset($options['jackshare_linkedin']) ? $options['jackshare_linkedin'] : 0 );
        $options['jackshare_viber']  = ( isset($options['jackshare_viber']) ? $options['jackshare_viber'] : 0 );
        $options['jackshare_whatsapp']  = ( isset($options['jackshare_whatsapp']) ? $options['jackshare_whatsapp'] : 0 );
        ?>
        <input id="jackshare_facebook" type="checkbox" name="jackshare_settings[jackshare_facebook]" value="1" <?php checked('1', $options['jackshare_facebook']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_facebook]"><?php esc_attr_e('Facebook', 'jackshare'); ?></label>
		<br>
	 	<input id="jackshare_messenger" type="checkbox" name="jackshare_settings[jackshare_messenger]" value="1" <?php checked('1', $options['jackshare_messenger']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_messenger]"><?php esc_attr_e('Send in Messenger', 'jackshare'); ?></label>
		<br>
		<input id="jackshare_twitter" type="checkbox" name="jackshare_settings[jackshare_twitter]" value="1" <?php checked('1', $options['jackshare_twitter']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_twitter]"><?php esc_attr_e('Twitter', 'jackshare'); ?></label>
		<br>
		<input id="jackshare_pinterest" type="checkbox" name="jackshare_settings[jackshare_pinterest]" value="1" <?php checked('1', $options['jackshare_pinterest']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_pinterest]"><?php esc_attr_e('Pinterest', 'jackshare'); ?></label>
		<br>
		<input id="jackshare_linkedin" type="checkbox" name="jackshare_settings[jackshare_linkedin]" value="1" <?php checked('1', $options['jackshare_linkedin']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_linkedin]"><?php esc_attr_e('Linkedin', 'jackshare'); ?></label>
        <br>
        <input id="jackshare_viber" type="checkbox" name="jackshare_settings[jackshare_viber]" value="1" <?php checked('1', $options['jackshare_viber']); ?>/>
        <label class="description" for="jackshare_settings[jackshare_viber]"><?php esc_attr_e('Viber', 'jackshare'); ?></label>
        <br>
        <input id="jackshare_whatsapp" type="checkbox" name="jackshare_settings[jackshare_whatsapp]" value="1" <?php checked('1', $options['jackshare_whatsapp']); ?>/>
        <label class="description" for="jackshare_settings[jackshare_whatsapp]"><?php esc_attr_e('WhatsApp', 'jackshare'); ?></label>
	<?php
    }
 

    function jackshare_options_page_section_facebook() {

        $settings_section = array(
            'jackshare_facebook_options_section',
            __( 'Facebook settings', 'jackshare' ),
            array(&$this, 'jackshare_facebook_options_section_callback' ),
            'jackshare_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section );

 	 	add_settings_field(
            'jackshare_social_share_counter',
            __( 'Display Counter', 'jackshare' ),
            array(&$this, 'jackshare_display_social_counter' ),
            'jackshare_options_page',
            'jackshare_facebook_options_section'
        );

        add_settings_field(
        	'jackshare_facebook_meta',
        	__( 'Open Graph Meta Tags', 'jackshare' ),
        	array(&$this, 'jackshare_facebook_open_graph_meta_tags' ),
        	'jackshare_options_page',
            'jackshare_facebook_options_section'
        );

        add_settings_field(
            'jackshare_facebook_app',
            __( 'Facebook App Setup', 'jackshare' ),
            array(&$this, 'jackshare_facebook_app_id_secret' ),
            'jackshare_options_page',
            'jackshare_facebook_options_section'
        );

        add_settings_field(
        	'jackshare_fb_cache_req',
        	__( 'Cache Request for', 'jackshare' ),
        	array(&$this, 'jackshare_facebook_cache_request' ),
        	'jackshare_options_page',
            'jackshare_facebook_options_section'
        );

    }

    /* Display Social Share Counter */
    function jackshare_display_social_counter() {

        $options = get_option( 'jackshare_settings' );
        $options['jackshare_social_share_counter'] = ( isset($options['jackshare_social_share_counter']) ? $options['jackshare_social_share_counter'] : 0 );
        $options['jackshare_display_reaction_count'] = ( isset($options['jackshare_display_reaction_count']) ? $options['jackshare_display_reaction_count'] : 0 );
        $options['jackshare_display_comment_counter'] = ( isset($options['jackshare_display_comment_counter']) ? $options['jackshare_display_comment_counter'] : 0 );
        ?>
        <input id="jackshare_social_share_counter" type="checkbox" name="jackshare_settings[jackshare_social_share_counter]" value="1" <?php checked('1', $options['jackshare_social_share_counter']); ?>/>
		<label class="description" for="jackshare_settings[display_share_counter]"><?php esc_attr_e('Enable Share counter', 'jackshare'); ?></label>
		<br>
		<input id="jackshare_display_reaction_count" type="checkbox" name="jackshare_settings[jackshare_display_reaction_count]" value="1" <?php checked('1', $options['jackshare_display_reaction_count']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_display_reaction_count]"><?php esc_attr_e('Enable Reaction counter', 'jackshare'); ?></label>
		<br>
		<input id="jackshare_display_comment_counter" type="checkbox" name="jackshare_settings[jackshare_display_comment_counter]" value="1" <?php checked('1', $options['jackshare_display_comment_counter']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_display_comment_counter]"><?php esc_attr_e('Enable Comment counter', 'jackshare'); ?></label>
		<br>
		<p class="description"><?php esc_attr_e( '(MULTIPLE SELECTION SUM THE SELECTED COUNTERS)','jackshare' ); ?></p>
	<?php

    }

    function jackshare_facebook_open_graph_meta_tags() {
	 	$options = get_option( 'jackshare_settings' );
 	 	$options['jackshare_facebook_meta'] = ( isset($options['jackshare_facebook_meta']) ? $options['jackshare_facebook_meta'] : 0 );
 	 	$options['add_fb_app_id'] = ( isset($options['add_fb_app_id']) ? $options['add_fb_app_id'] : '' );
 	 	$options['add_fb_app_secret'] = ( isset($options['add_fb_app_secret']) ? $options['add_fb_app_secret'] : '' );
	 	?>
	 	<input id="jackshare_settings[jackshare_facebook_meta]" type="checkbox" name="jackshare_settings[jackshare_facebook_meta]" value="1" <?php checked('1', $options['jackshare_facebook_meta']); ?>/>
		<label class="description" for="jackshare_settings[jackshare_facebook_meta]"><?php esc_attr_e('Enable Facebook Meta Tags On Header', 'jackshare'); ?></label>
		<br>
		<p class="description"><?php esc_attr_e( '(PLEASE IGNORE THIS OPTION IF YOU ARE USING A SEO PLUGIN ALREADY!)','jackshare' ); ?></p>
		<br>
		
	<?php

    }

    function jackshare_facebook_app_id_secret(){
        $options = get_option('jackshare_settings');
        ?>
        <input class="regular-text" id="jackshare_settings[add_fb_app_id]" name="jackshare_settings[add_fb_app_id]" type="text" value="<?php echo $options['add_fb_app_id']; ?>" placeholder="<?php _e('e.g', 'jackshare'); ?> 1877030522310000"/>
        <span class="description" for="jackshare_settings[add_fb_app_id]"><?php esc_attr_e('Αdd your Facebook app_id here to display the shared links properly', 'jackshare'); ?></span>
        <br>
        <input class="regular-text" id="jackshare_settings[add_fb_app_secret]" name="jackshare_settings[add_fb_app_secret]" type="text" value="<?php echo $options['add_fb_app_secret']; ?>" placeholder="<?php _e('e.g', 'jackshare'); ?> 1877030522310000"/>
        <span class="description" for="jackshare_settings[add_fb_app_secret]"><?php esc_attr_e('Αdd your Facebook app_secret here to display the shared links properly', 'jackshare'); ?></span>
        <p class="description"><?php printf( __( '(Follow <a href="%s" target="_blank">this link</a> to find instructions on how to setup your own app_id. If you are already familiar with this process connect to <a href="%s" target="_blank">Facebook for developers</a>.)',
               'jackshare' ), esc_url( 'https://developers.facebook.com/docs/apps/register#app-id' ), esc_url( 'https://developers.facebook.com/apps/' ) ); ?>      
        </p>
    <?php
    }

    function jackshare_facebook_cache_request() {
    	$options = get_option( 'jackshare_settings' );
    	$options['jackshare_fb_cache_req'] = ( isset($options['jackshare_fb_cache_req']) ? $options['jackshare_fb_cache_req'] : '30' );
        ?>
        <select id='jackshare_facebook_share_cache_request' name='jackshare_settings[jackshare_fb_cache_req]'>
            <option value='30' <?php selected($options['jackshare_fb_cache_req'], '30' ); ?>><?php _e( '30 Minutes', 'jackshare' ); ?></option>
            <option value='60' <?php selected($options['jackshare_fb_cache_req'], '60' ); ?>><?php _e( '1 Hour', 'jackshare' ); ?></option>
          	<option value='120' <?php selected($options['jackshare_fb_cache_req'], '120' ); ?>><?php _e( '2 Hours', 'jackshare' ); ?></option>
        </select>

	<?php
    }

    function jackshare_options_page_section_twitter() {

	 	$settings_section = array(
            'jackshare_twitter_options_section',
            __( 'Twitter settings', 'jackshare' ),
            array(&$this, 'jackshare_twitter_options_section_callback' ),
            'jackshare_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section );

     	add_settings_field(
        	'jackshare_twitter_via',
        	__( 'Twitter Username', 'jackshare' ),
        	array(&$this, 'jackshare_twitter_via_settings' ),
        	'jackshare_options_page',
            'jackshare_twitter_options_section'
        );


    }

 	function jackshare_twitter_via_settings() {
	 	$options = get_option( 'jackshare_settings' );
 	 	$options['jackshare_twitter_via'] = ( isset($options['jackshare_twitter_via']) ? $options['jackshare_twitter_via'] : '' );
	 	?>
		<input class="regular-text" id="jackshare_settings[jackshare_twitter_via]" name="jackshare_settings[jackshare_twitter_via]" type="text" value="<?php echo $options['jackshare_twitter_via']; ?>" placeholder="<?php _e('e.g', 'jackshare'); ?> jackshare"/>
		<span class="description" for="jackshare_settings[jackshare_twitter_via]"><?php esc_attr_e('Insert your Twitter username to display when a link will be shared', 'jackshare'); ?></span>
		<p class="description"><?php esc_attr_e( '(Insert only your username without @ or the whole link to your twitter profile.)','jackshare' ); ?></p>
		<br>
	<?php

    }





}




?>