<?php
/*
Plugin Name: Dakwak Widget
Plugin URI: http://dakwak.com
Description: Translate your website with crowdsourcing.
Version: 0.01
Author: Dakwak
Author URI: http://dakwak.com
License: A "Slug" license name e.g. GPL2
*/

class wp_dakwak extends WP_Widget {

    var $domain = 'http://f3.f.us.dakwak.com/api/';
    var $userExists = false;
    var $website_apikey = '';
    var $messages;


    function wp_dakwak() {
        parent::WP_Widget(false, $name = __('Dakwak Languages', 'wp_dakwak') );
    }

    // widget form creation
    function form($instance) {

    // Check values
        if( $instance) {
            $email = ($instance['email']);
            $url = ($instance['url']);
            $from_lang = $instance['from_lang'];
            $to_lang = $instance['to_lang'];
            $website_apikey = ($instance['website_apikey']);
            $instance['app'] = 'wp';

        } else {
            $email = get_option('admin_email');
            $url =  get_site_url();
            $from_lang = '';
            $to_lang = '';
            $website_apikey = '';
        }

        ?>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#<?php echo $this->get_field_id('from_lang')?> option[value="<?php echo $from_lang ?>"]').attr('selected', 'selected');

                <?php if(is_array($to_lang)): foreach($to_lang as $lang): ?>
                jQuery('#<?php echo $this->get_field_id('to_lang')?> option[value="<?php echo $lang ?>"]').attr('selected', 'selected');
                <?php endforeach; else: ?>
                jQuery('#<?php echo $this->get_field_id('to_lang')?> option[value="<?php echo $to_lang ?>"]').attr('selected', 'selected');
                <?php endif; ?>
            });
        </script>

        <?php

            if(!empty($instance['messages'])) {
                foreach($instance['messages'] as &$msg) {
                    print $msg;
                }
            }

        ?>

        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', 'wp_dakwak'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Website URL:', 'wp_dakwak'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('from_lang'); ?>"><?php _e('Original Language:', 'wp_dakwak'); ?></label>
            <select name="<?php echo $this->get_field_name('from_lang'); ?>" id="<?php echo $this->get_field_id('from_lang'); ?>">
                <option value="af">Afrikaans</option><option value="el">Greek</option><option value="ro">Romanian</option><option value="sq">Albanian</option><option value="iw">Hebrew</option><option value="ru">Russian</option><option value="ar">Arabic</option><option value="hi">Hindi</option><option value="sr">Serbian</option><option value="be">Belarusian</option><option value="hu">Hungarian</option><option value="sk">Slovak</option><option value="bg">Bulgarian</option><option value="is">Icelandic</option><option value="sl">Slovenian</option><option value="ca">Catalan</option><option value="id">Indonesian</option><option value="es">Spanish</option><option value="zh-CN">Chinese (Simplified)</option><option value="ga">Irish</option><option value="sw">Swahili</option><option value="hr">Croatian</option><option value="it">Italian</option><option value="sv">Swedish</option><option value="cs">Czech</option><option value="ja">Japanese</option><option value="th">Thai</option><option value="da">Danish</option><option value="ko">Korean</option><option value="tr">Turkish</option><option value="nl">Dutch</option><option value="lv">Latvian</option><option value="uk">Ukrainian</option><option value="en">English</option><option value="lt">Lithuanian</option><option value="vi">Vietnamese</option><option value="et">Estonian</option><option value="mk">Macedonian</option><option value="cy">Welsh</option><option value="tl">Filipino</option><option value="ms">Malay</option><option value="yi">Yiddish</option><option value="fi">Finnish</option><option value="mt">Maltese</option><option value="ur">Urdu</option><option value="fr">French</option><option value="fa">Persian</option><option value="zh-TW">Chinese (Traditional)</option><option value="gl">Galician</option><option value="pl">Polish</option><option value="fr-CA">French (Canada)</option><option value="de">German</option><option value="pt-PT">Portuguese (Europe)</option><option value="es-LA">Spanish (Latin America)</option><option value="pt-BR">Portuguese (Brazil)</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('to_lang'); ?>"><?php _e('Target Languages:', 'wp_dakwak'); ?></label>
            <select multiple="multiple" name="<?php echo $this->get_field_name('to_lang'); ?>[]" id="<?php echo $this->get_field_id('to_lang'); ?>">
                <option value="af">Afrikaans</option><option value="el">Greek</option><option value="ro">Romanian</option><option value="sq">Albanian</option><option value="iw">Hebrew</option><option value="ru">Russian</option><option value="ar">Arabic</option><option value="hi">Hindi</option><option value="sr">Serbian</option><option value="be">Belarusian</option><option value="hu">Hungarian</option><option value="sk">Slovak</option><option value="bg">Bulgarian</option><option value="is">Icelandic</option><option value="sl">Slovenian</option><option value="ca">Catalan</option><option value="id">Indonesian</option><option value="es">Spanish</option><option value="zh-CN">Chinese (Simplified)</option><option value="ga">Irish</option><option value="sw">Swahili</option><option value="hr">Croatian</option><option value="it">Italian</option><option value="sv">Swedish</option><option value="cs">Czech</option><option value="ja">Japanese</option><option value="th">Thai</option><option value="da">Danish</option><option value="ko">Korean</option><option value="tr">Turkish</option><option value="nl">Dutch</option><option value="lv">Latvian</option><option value="uk">Ukrainian</option><option value="en">English</option><option value="lt">Lithuanian</option><option value="vi">Vietnamese</option><option value="et">Estonian</option><option value="mk">Macedonian</option><option value="cy">Welsh</option><option value="tl">Filipino</option><option value="ms">Malay</option><option value="yi">Yiddish</option><option value="fi">Finnish</option><option value="mt">Maltese</option><option value="ur">Urdu</option><option value="fr">French</option><option value="fa">Persian</option><option value="zh-TW">Chinese (Traditional)</option><option value="gl">Galician</option><option value="pl">Polish</option><option value="fr-CA">French (Canada)</option><option value="de">German</option><option value="pt-PT">Portuguese (Europe)</option><option value="es-LA">Spanish (Latin America)</option><option value="pt-BR">Portuguese (Brazil)</option>
            </select>
        </p>

        <?php if($website_apikey): ?>
        <label for="<?php echo $this->get_field_id('website_apikey'); ?>"><?php _e('Dakwak API Key:', 'wp_dakwak'); ?></label>
        <input disabled="disabled" class="widefat" id="<?php echo $this->get_field_id('website_apikey'); ?>" name="<?php echo $this->get_field_name('website_apikey'); ?>" type="text" value="<?php echo $website_apikey; ?>" />
        <?php endif; ?>

    <?php
    }
    // widget update
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['email'] = is_email($new_instance['email']);
        $instance['url'] = ($new_instance['url']);
        $instance['from_lang'] = ($new_instance['from_lang']);
        $instance['to_lang'] = $new_instance['to_lang'];
        $instance['website_apikey'] = $new_instance['website_apikey'];
        $instance['messages'] = array();

        $args['body'] = $new_instance;
        $args['body']['app'] = 'wp';
        $args['body']['to_lang'] = is_array($new_instance['to_lang']) ? implode(',', $new_instance['to_lang']) : $new_instance['to_lang'];

        $url = "{$this->domain}users/exists.json";
        $request = wp_remote_get($url, $args);
        $response = json_decode($request['body']);


        if(isset($response->exists) && $response->exists == true) {
            $this->userExists = true;
            $this->website_apikey = $response->website_apikey;
            $instance['website_apikey'] = $response->website_apikey;
        }

        if($this->userExists) {
            $url = "{$this->domain}websites/{$instance['website_apikey']}.json";
            $request = wp_remote_post($url, $args);
            $response = json_decode($request['body']);
            $response = (array)$response;

            if(is_wp_error($request) || isset($response['error'])) {
                $html = '<div class="updated">';
                $html .= __( 'Saving settings failed: ' . $response['error'], 'wp_dakwak' );
                $html .= '</div><!-- /#post-error -->';
                $instance['messages'][] = $html;
            } else {
                $html = '<div class="updated">';
                $html .= __( 'Settings updated.', 'wp_dakwak' );
                $html .= '</div><!-- /#post-error -->';
                $instance['messages'][] = $html;
            }

        } else {
            $url = "{$this->domain}users/new.json";
            $request = wp_remote_post($url, $args);
            $response = json_decode($request['body']);
            $response = (array)$response;

            if(is_wp_error($request) || isset($response['error'])) {
                $html = '<div class="updated">';
                $html .= __( 'Saving settings failed: ' . $response['error'], 'wp_dakwak' );
                $html .= '</div><!-- /#post-error -->';
                $instance['messages'][] = $html;
            } else {
                $html = '<div class="updated">';
                $html .= __( 'New dakwak user created', 'wp_dakwak' );
                $html .= '</div><!-- /#post-error -->';
                $instance['messages'][] = $html;
            }

            if(isset($response->website_apikey)) {
                $instance['website_apikey'] = $response->website_apikey;
            }
        }

        return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );

        $url = "{$this->domain}websites/{$instance['website_apikey']}/widget";

        $request = wp_remote_get($url);

        echo $before_widget;

        if ( is_wp_error( $request ) ) {
            $error_message = $request->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            echo $request['body'];
        }

        echo $after_widget;
    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_dakwak");'));