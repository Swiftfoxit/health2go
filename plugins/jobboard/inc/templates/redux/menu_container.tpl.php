<?php
/**
 * The template for the menu container of the panel.
 *
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author 	Redux Framework
 * @package 	ReduxFramework/Templates
 * @version:    3.5.4
 */
global $opt_theme_options;
?>
<div class="redux-sidebar">
	<div class="redux-sidebar-head">
		<h3 class="abcore-redux-name"><?php echo esc_html__('JobBoard', JB_TEXT_DOMAIN) ?></h3>
		<span>
			<?php echo esc_attr( JB_VERSION ); ?>
		</span>
	</div>
    <ul class="redux-group-menu">
    <?php
            foreach ( $this->parent->sections as $k => $section ) {
                $title = isset ( $section[ 'title' ] ) ? $section[ 'title' ] : '';

                $skip_sec = false;
                foreach ( $this->parent->hidden_perm_sections as $num => $section_title ) {
                    if ( $section_title == $title ) {
                        $skip_sec = true;
                    }
                }

                if ( isset ( $section[ 'customizer_only' ] ) && $section[ 'customizer_only' ] == true ) {
                    continue;
                }

                if ( false == $skip_sec ) {
                    echo $this->parent->section_menu ( $k, $section );
                    $skip_sec = false;
                }
            }

            /**
             * action 'redux-page-after-sections-menu-{opt_name}'
             *
             * @param object $this ReduxFramework
             */
            do_action ( "redux-page-after-sections-menu-{$this->parent->args[ 'opt_name' ]}", $this );

            /**
             * action 'redux/page/{opt_name}/menu/after'
             *
             * @param object $this ReduxFramework
             */
            do_action ( "redux/page/{$this->parent->args[ 'opt_name' ]}/menu/after", $this );
    ?>
    </ul>
</div>
