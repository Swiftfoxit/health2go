<?php 
    /* get categories */
        $taxo = 'team_categories';
        $_category = array();
        if(!isset($atts['cat']) || $atts['cat']==''){
            $terms = get_terms($taxo);
            foreach ($terms as $cat){
                $_category[] = $cat->term_id;
            }
        } else {
            $_category  = explode(',', $atts['cat']);
        }
        $atts['categories'] = $_category;
?>
<div class="cms-grid-wraper team-layout1 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php if($atts['filter']=="true" and $atts['layout']=='masonry'):?>
        <div class="cms-grid-filter">
            <ul>
                <li><a class="active" href="#" data-group="all"><?php esc_html_e('All', "wp-recruitment"); ?></a></li>
                <?php foreach($atts['categories'] as $category):?>
                    <?php $term = get_term( $category, $taxo );?>
                    <li><a href="#" data-group="<?php echo esc_attr('category-'.$term->slug);?>">
                            <?php echo esc_html($term->name);?>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    <div class="clearfix row <?php echo esc_attr($atts['grid_class']);?>">
        <?php
        $posts = $atts['posts'];
        $size = 'full';
        while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            $groups[] = '"all"';
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = '"category-'.$category->slug.'"';
            }
            ?>
            <div class="cms-grid-item <?php echo esc_attr($atts['item_class']);?>" data-groups='[<?php echo implode(',', $groups);?>]'>
                <div class="cms-grid-item-inner">
                    <div class="cms-team-image">
                        <?php 
                            if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
                                $class = ' has-thumbnail';
                                $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
                                $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
                            else:
                                $class = ' no-image';
                                $thumbnail_url[0] = get_template_directory_uri(). '/assets/images/no-image.jpg';
                                $thumbnail = '<img src="'.esc_url(get_template_directory_uri(). '/assets/images/no-image.jpg').'" alt="'.get_the_title().'" />';
                            endif;
                            echo '<div class="cms-team-media '.esc_attr($class).'">'.$thumbnail.'</div>';
                        ?>
                    </div>
                    <div class="cms-team-meta clearfix">
                        <h3 class="cms-team-title left"><?php the_title();?></h3>
                        <div class="cms-team-social right">
                            <?php
                                for($i=1;$i<5;$i++){
                                    $team_icon = get_post_meta(get_the_ID(), 'team_icon'.$i, true);
                                    $team_link = get_post_meta(get_the_ID(), 'team_link'.$i, true);
                                    if(!empty($team_icon) && !empty($team_link)): ?>
                                        <a href="<?php echo esc_url($team_link);?>"><i class="<?php echo esc_attr($team_icon);?>"></i></a> 
                                    <?php endif;
                                }
                            ?>
                        </div>
                    </div>
                    <div class="cms-team-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>