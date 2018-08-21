<?php
/**
 * Custom Woocommerce shop page.
 */

get_header(); ?>

<div id="primary" class="container">
    <div class="row">
        <div id="content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <main id="main" class="site-main">

            	<?php woocommerce_content(); ?>

            </main><!-- .site-main -->
        </div>
    </div>
</div><!-- .content-area -->

<?php get_footer(); ?>