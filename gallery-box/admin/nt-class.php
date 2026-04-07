<?php

/**
 * Reusable admin notice recommending WPEPP plugin.
 *
 * Wrapped in class_exists() so multiple plugins can include
 * this file without conflict — only the first one loaded wins.
 *
 * @link    https://wpthemespace.com
 * @since   2.0.0
 * @package WpSpace
 * @author  Noor Alam
 */

if ( ! class_exists( 'WpSpace_Recommend_WPEPP' ) ) :

class WpSpace_Recommend_WPEPP {

    const PLUGIN_SLUG     = 'wp-edit-password-protected';
    const PLUGIN_BASENAME = 'wp-edit-password-protected/wp-edit-password-protected.php';
    const DISMISS_OPTION  = 'wpspace_wpepp_dismiss';
    const AJAX_ACTION     = 'wpspace_activate_wpepp';
    const NONCE_DISMISS   = 'wpspace_wpepp_dismiss_nonce';

    public function __construct() {
        add_action( 'admin_notices', array( $this, 'render_notice' ) );
        add_action( 'admin_init',    array( $this, 'handle_dismiss' ) );
        add_action( 'wp_ajax_' . self::AJAX_ACTION, array( $this, 'ajax_activate' ) );
    }

    /**
     * Render the recommendation notice.
     */
    public function render_notice() {

        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        if ( get_option( self::DISMISS_OPTION, false ) ) {
            return;
        }

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( is_plugin_active( self::PLUGIN_BASENAME ) ) {
            return;
        }

        $installed = is_dir( WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG );

        if ( $installed ) {
            $button_text = esc_html__( 'Activate Now', 'click-to-top' );
            $data_action = 'activate';
        } else {
            $button_text = esc_html__( 'Install & Activate', 'click-to-top' );
            $data_action = 'install';
        }

        $dismiss_url = wp_nonce_url(
            add_query_arg( 'wpspace_wpepp_dismiss', '1' ),
            self::NONCE_DISMISS
        );

        $nonce_activate = wp_create_nonce( 'wpspace-activate-' . self::PLUGIN_SLUG );
        $nonce_updates  = wp_create_nonce( 'updates' );

        wp_enqueue_script( 'plugin-install' );
        wp_enqueue_script( 'updates' );

        $uid = 'wpspace-wpepp-' . wp_rand( 1000, 9999 );
        ?>
        <div id="<?php echo esc_attr( $uid ); ?>" class="notice notice-info is-dismissible" style="padding:14px 20px 18px; border-left-color:#2271b1;">
            <p style="font-size:14px; margin-bottom:10px;">
                <span style="font-size:16px; margin-right:4px;">&#x1f6e1;</span>
                <strong><?php esc_html_e( 'Protect Your Site — Free Security Plugin', 'click-to-top' ); ?></strong>
            </p>
            <p style="margin-bottom:12px;">
                <?php esc_html_e( 'Your WordPress site may be vulnerable to brute force attacks, AI content scrapers, and unauthorized access.', 'click-to-top' ); ?>
                <strong>WPEPP</strong> <?php esc_html_e( 'fixes that in one click:', 'click-to-top' ); ?>
            </p>
            <ul style="margin:0 0 14px 18px; list-style:disc; line-height:1.8;">
                <li><?php esc_html_e( 'Limit Login Attempts & block brute force attacks', 'click-to-top' ); ?></li>
                <li><?php esc_html_e( 'Block AI Crawlers — stop GPTBot, CCBot & others from scraping your content', 'click-to-top' ); ?></li>
                <li><?php esc_html_e( 'Disable XML-RPC, hide WP version & block user enumeration', 'click-to-top' ); ?></li>
                <li><?php esc_html_e( 'Customize the login page with live preview', 'click-to-top' ); ?></li>
                <li><?php esc_html_e( 'Password protect pages & lock your entire site', 'click-to-top' ); ?></li>
            </ul>
            <p style="margin:0;">
                <button type="button"
                    class="button button-primary wpspace-wpepp-btn"
                    data-notice="<?php echo esc_attr( $uid ); ?>"
                    data-action="<?php echo esc_attr( $data_action ); ?>"
                    data-slug="<?php echo esc_attr( self::PLUGIN_SLUG ); ?>"
                    data-basename="<?php echo esc_attr( self::PLUGIN_BASENAME ); ?>"
                    data-install-nonce="<?php echo esc_attr( $nonce_updates ); ?>"
                    data-activate-nonce="<?php echo esc_attr( $nonce_activate ); ?>"
                    style="margin-right:8px; font-weight:600;">
                    <?php echo $button_text; // Already escaped. ?>
                </button>

                <a href="<?php echo esc_url( $dismiss_url ); ?>" style="color:#787c82; text-decoration:none;">
                    <?php esc_html_e( 'No thanks', 'click-to-top' ); ?>
                </a>
                <span class="wpspace-wpepp-status" style="margin-left:10px; font-style:italic; color:#666;"></span>
            </p>
        </div>
        <?php
        $this->inline_script();
    }

    /**
     * Output the inline JS once per page load.
     */
    private function inline_script() {
        static $printed = false;
        if ( $printed ) {
            return;
        }
        $printed = true;

        $ajax_action = self::AJAX_ACTION;
        $i18n = array(
            'installing'  => esc_js( __( 'Installing...', 'click-to-top' ) ),
            'activating'  => esc_js( __( 'Activating...', 'click-to-top' ) ),
            'active'      => esc_js( __( 'Active!', 'click-to-top' ) ),
            'installBtn'  => esc_js( __( 'Install & Activate', 'click-to-top' ) ),
            'activateBtn' => esc_js( __( 'Activate Now', 'click-to-top' ) ),
            'installFail' => esc_js( __( 'Install failed.', 'click-to-top' ) ),
            'activeFail'  => esc_js( __( 'Activation failed.', 'click-to-top' ) ),
            'success'     => esc_js( __( 'Plugin activated successfully.', 'click-to-top' ) ),
        );
        ?>
        <script>
        (function(){
            var i18n = <?php echo wp_json_encode( $i18n ); ?>;
            document.querySelectorAll('.wpspace-wpepp-btn').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    var noticeEl = document.getElementById(btn.getAttribute('data-notice'));
                    var statusEl = noticeEl ? noticeEl.querySelector('.wpspace-wpepp-status') : null;
                    var action   = btn.getAttribute('data-action');
                    var slug     = btn.getAttribute('data-slug');
                    var basename = btn.getAttribute('data-basename');

                    btn.disabled = true;

                    if ( action === 'install' ) {
                        btn.textContent = i18n.installing;
                        if(statusEl) statusEl.textContent = '';
                        doInstall(btn, statusEl, noticeEl, slug, basename);
                    } else {
                        btn.textContent = i18n.activating;
                        if(statusEl) statusEl.textContent = '';
                        doActivate(btn, statusEl, noticeEl, basename);
                    }
                });
            });

            function doInstall(btn, statusEl, noticeEl, slug, basename){
                wp.updates.ajax('install-plugin',{
                    slug: slug,
                    success: function(){
                        btn.textContent = i18n.activating;
                        doActivate(btn, statusEl, noticeEl, basename);
                    },
                    error: function(r){
                        if(r.errorCode === 'folder_exists'){
                            btn.textContent = i18n.activating;
                            doActivate(btn, statusEl, noticeEl, basename);
                            return;
                        }
                        btn.textContent = i18n.installBtn;
                        btn.disabled = false;
                        if(statusEl){ statusEl.textContent = r.errorMessage || i18n.installFail; statusEl.style.color='#d63638'; }
                    }
                });
            }

            function doActivate(btn, statusEl, noticeEl, basename){
                var fd = new FormData();
                fd.append('action','<?php echo esc_js( $ajax_action ); ?>');
                fd.append('plugin', basename);
                fd.append('_wpnonce', btn.getAttribute('data-activate-nonce'));

                fetch(ajaxurl,{method:'POST',body:fd,credentials:'same-origin'})
                    .then(function(r){return r.json();})
                    .then(function(r){
                        if(r.success){
                            btn.textContent = i18n.active;
                            btn.classList.remove('button-primary');
                            btn.style.color='#00a32a';
                            btn.style.borderColor='#00a32a';
                            if(statusEl){ statusEl.textContent = '\u2713 '+i18n.success; statusEl.style.color='#00a32a'; }
                            setTimeout(function(){ if(noticeEl) noticeEl.style.display='none'; },3000);
                        } else {
                            btn.textContent = i18n.activateBtn;
                            btn.disabled = false;
                            if(statusEl){ statusEl.textContent = (r.data&&r.data.message)||i18n.activeFail; statusEl.style.color='#d63638'; }
                        }
                    })
                    .catch(function(){
                        btn.textContent = i18n.activateBtn;
                        btn.disabled = false;
                        if(statusEl){ statusEl.textContent = i18n.activeFail; statusEl.style.color='#d63638'; }
                    });
            }
        })();
        </script>
        <?php
    }

    /**
     * Handle the dismiss (nonce-protected GET request).
     */
    public function handle_dismiss() {
        if (
            isset( $_GET['wpspace_wpepp_dismiss'] )
            && '1' === $_GET['wpspace_wpepp_dismiss']
            && isset( $_GET['_wpnonce'] )
            && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), self::NONCE_DISMISS )
            && current_user_can( 'install_plugins' )
        ) {
            update_option( self::DISMISS_OPTION, 1, false );
            wp_safe_redirect( remove_query_arg( array( 'wpspace_wpepp_dismiss', '_wpnonce' ) ) );
            exit;
        }
    }

    /**
     * AJAX: activate the plugin without page reload.
     */
    public function ajax_activate() {
        check_ajax_referer( 'wpspace-activate-' . self::PLUGIN_SLUG );

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'click-to-top' ) ) );
        }

        $plugin = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';

        if ( empty( $plugin ) || $plugin !== self::PLUGIN_BASENAME ) {
            wp_send_json_error( array( 'message' => __( 'Invalid plugin.', 'click-to-top' ) ) );
        }

        $result = activate_plugin( $plugin );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success();
    }
}

new WpSpace_Recommend_WPEPP();

endif; // class_exists

// ---------- Theme recommendation notice (themes.php only) ----------
if ( ! function_exists( 'spacehide_go_me' ) ) :
    function spacehide_go_me() {
        global $pagenow;
        if ( $pagenow !== 'themes.php' ) {
            return;
        }

        $url = 'https://wpthemespace.com/product-category/pro-theme/';
        ?>
        <div class="notice notice-success is-dismissible" style="padding:10px 15px 20px;">
            <p>
                <strong><span style="color:red;"><?php esc_html_e( 'Hi Buddy!! Recommended WordPress Theme for you:', 'click-to-top' ); ?></span>
                <span style="color:green;"><?php esc_html_e( 'If you find a Secure, SEO friendly, full functional premium WordPress theme for your site then', 'click-to-top' ); ?></span></strong>
                <a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php esc_html_e( 'see here', 'click-to-top' ); ?></a>.
            </p>
            <a target="_blank" class="button button-danger" href="<?php echo esc_url( $url ); ?>" style="margin-right:10px;">
                <?php esc_html_e( 'View WordPress Theme', 'click-to-top' ); ?>
            </a>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'spacehide_go_me' );
endif;
