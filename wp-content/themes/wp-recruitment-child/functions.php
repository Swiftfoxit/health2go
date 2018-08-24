<?php

/**
 * add child styles.
 *
 * @author CMSSuperHeroes
 * @since 1.1.1
 */
function wp_recruitment_enqueue_styles()
{
    $parent_style = 'wp-recruitment-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(
        $parent_style
    ));
	
	wp_register_style('bootstrap-datetimepicker', get_stylesheet_directory_uri() . '/css/bootstrap-datetimepicker.min.css');
	wp_register_style('zinput', get_stylesheet_directory_uri() . '/css/zInput_default_stylesheet.css');
	wp_register_style('bootstrap-slider', get_stylesheet_directory_uri() . '/css/bootstrap-slider.min.css');
	wp_register_script('bootstrap-datetimepicker', get_stylesheet_directory_uri() . '/js/bootstrap-datetimepicker.min.js');
	wp_register_script('bootstrap-slider', get_stylesheet_directory_uri() . '/js/bootstrap-slider.min.js');
	
}

add_action('wp_enqueue_scripts', 'wp_recruitment_enqueue_styles');

/**
 * Load vc template dir.
 *
 * @author CMSSuperHeroes
 * @since 1.1.1
 */
if (function_exists("vc_set_shortcodes_templates_dir")) {
    vc_set_shortcodes_templates_dir(get_stylesheet_directory() . "/vc_templates/");
}

/*user approval,set role and login control*/
add_action('jobboard_user_registered','jobboard_user_registered_assign_role');
function jobboard_user_registered_assign_role($user){
	$role= get_user_meta($user->ID, '_jobboard_register_role', true);
	
	wp_update_user(array('ID' => $user->ID, 'role' => $role));
}
add_action('wp','remove_profile_extra_field');
function remove_profile_extra_field(){
if(class_exists('JobBoard')){
	$JobBoard = JobBoard::instance();
	
	remove_filter('jobboard_candidate_profile_fields', array($JobBoard->admin, 'fields_video'), 8);
	remove_filter('jobboard_employer_profile_fields', array($JobBoard->admin, 'fields_video'), 8);
	
	remove_filter('jobboard_candidate_profile_fields', array($JobBoard->admin, 'fields_candidate_social'), 10);
	remove_filter('jobboard_employer_profile_fields', array($JobBoard->admin, 'fields_employer_social'), 10);
	
	
}
}
/*user approval,set role and login control*/

/*remove specilalism field from registration form and rename accout type field*/
add_filter('jobboard-register-fields','remove_jobboard_register_fields');
function remove_jobboard_register_fields($fields){
	
	$fields['user_type'] = array(
                    'id'            => 'user_type',
                    'title'         => esc_html__('Account Type', 'jobboard-register' ),
                    'subtitle'      => esc_html__('Select account type.', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Account Type','jobboard-register'),
                    'type'          => 'select',
                    'require'       => true,
                    'value'         => '',
                    'options'       => array(
                        'candidate' => esc_html__('Service Provider', 'jobboard-register'),
                        'employer'  => esc_html__('Hit', 'jobboard-register'),
                    ),
                );
	
	unset($fields['job_specialisms']);
	return $fields;
}

/*remove specilalism field from registration form and rename accout type field*/

/*Change role name*/
add_action('init', 'change_role_name');
function change_role_name() {
global $wp_roles;
if ( ! isset( $wp_roles ) )
$wp_roles = new WP_Roles();
if(isset($wp_roles->roles['jobboard_role_candidate']) && isset($wp_roles->roles['jobboard_role_employer'])){
	$wp_roles->roles['jobboard_role_candidate']['name'] = 'Service Provider';
	$wp_roles->roles['jobboard_role_employer']['name'] = 'Hit';
	
	$wp_roles->role_names['jobboard_role_candidate'] = 'Service Provider';
	$wp_roles->role_names['jobboard_role_employer'] = 'Hit';
}
}
add_filter('jobboard_admin_sections','rename_jobboard_admin_sections');
function rename_jobboard_admin_sections($sections){
	$sections['general-setting']['fields'][1]['title']='Companies & Service Providers Listing';
	$sections['general-setting']['fields'][1]['subtitle']='Number of Companies or Service Provider to show per page.';
	
	$sections['page-setting']['fields'][1]['title']='Hit Listing';
	$sections['page-setting']['fields'][1]['subtitle']='Page for Hit listing';
	$sections['page-setting']['fields'][2]['title']='Service Provider Listing';
	$sections['page-setting']['fields'][2]['subtitle']='Page for Service Provider listing';
	
	$sections['candidate-custom-fields']['title']='Service Providers';
	$sections['candidate-custom-fields']['desc']='Service Providers profile form';
	$sections['employer-custom-fields']['title']='Hit';
	$sections['employer-custom-fields']['desc']='Hit profile form';
	
	return $sections;
}

add_action( 'wp_loaded', 'change_package_labels', 20 );

function change_package_labels()
{
    $employer_object = get_post_type_object( 'jb-package-employer' );

    if ( ! $employer_object )
        return FALSE;

    // see get_post_type_labels()   
    $employer_object->labels->all_items          = 'Hit';
	
	$candidate_object = get_post_type_object( 'jb-package-candidate' );

    if ( ! $candidate_object )
        return FALSE;

    // see get_post_type_labels()   
    $candidate_object->labels->all_items          = 'Service Provider';

    return TRUE;
}

/*Change role name*/


/*remove package from employer dashboard and rename navigation menu*/

add_filter('jobboard_employer_navigation_args','jobboard_employer_navigation_change');
function jobboard_employer_navigation_change($args){
	
	if(!empty($args)){
		foreach($args as $key=>$arg){
			if($arg['id']=='jobs'){
				$args[$key]['title']='Request History';
			}
			if($arg['id']=='new'){
				$args[$key]['title']='Create Job Request';
			}
		}
	}
	return $args;
}


add_action('wp','remove_package_for_employer');
function remove_package_for_employer(){
if(class_exists('JB_Package')){
	$JB_Package = JB_Package::instance();
	remove_filter( 'jobboard_employer_navigation_args', array( $JB_Package, 'add_endpoint_menu' ) );
	}
}
add_action( 'admin_menu', 'remove_employer_package_menu_item');
function remove_employer_package_menu_item(){
	global $submenu;
	$key='edit.php?post_type=jobboard-post-jobs';
	$menu_key=0;
	if(!empty($submenu[ $key ] )){
	foreach ( $submenu[ $key ] as $k => $menu ) {
		if ( $menu[2] == 'edit.php?post_type=jb-package-employer' ) {
			$menu_key=$k;
			break;
		}
	}}
	if(!empty($menu_key))
		unset($submenu[$key][$menu_key]);
		
	return $submenu;
	
}
/*remove package from employer dashboard*/

/*Change post job fields for employer*/
 //add_filter('jobboard_add_job_fields','change_jobboard_add_job_fields');
function change_jobboard_add_job_fields($fields){
		
	//Regenerate fields
	$fields=array(
		 array(
            'id'         => 'post-heading',
            'title'      => esc_html__('Create Job Request' ),
            'subtitle'   => esc_html__('Make sure you have completed all required fields (*), before job request.' ),
            'type'       => 'heading',
            'heading'    => 'h3'
        ),
        array (
            'id'         => 'post_title',
            'title'      => esc_html__('Job Title *' ),
            'subtitle'   => esc_html__('Enter your job title' ),
            'notice'     => esc_html__('is required !'),
            'type'       => 'text',
            'require'    => 1,
            'col'        => 12,
            'placeholder'=> esc_html__('Job Title *' )
        ),
		array (
            'id'         => 'specialisms',
            'name'       => 'specialisms[]',
            'title'      => esc_html__('Specialisms & Skill' ),
            'subtitle'   => esc_html__('Select specialisms and skill for job.' ),
            'notice'     => esc_html__('is required !'),
            'placeholder'=> esc_html__('Specialisms & Skill *' ),
            'type'       => 'select',
            'multi'      => true,
            'col'        => 12,
            'require'    => 1,
            'options'    => jb_get_specialism_options(),
        ),
		array (
            'id'         => 'specialisms2',
            'name'       => 'specialisms2[]',
            'title'      => esc_html__('Specialisms & Skill' ),
            'subtitle'   => esc_html__('Select specialisms and skill for job.' ),
            'notice'     => esc_html__('is required !'),
            'placeholder'=> esc_html__('Specialisms & Skill *' ),
            'type'       => 'checkbox',
            'multi'      => true,
            'col'        => 12,
            'require'    => 1,
            'options'    => jb_get_specialism_options(),
        ),
		array(
            'id'         => 'locations',
            'type'       => 'location',
            'title'      => esc_html__('Job Address' ),
            'subtitle'   => esc_html__('Select job address.' ),
            'taxonomy'   => 'jobboard-tax-locations',
            'options'    => array(
                array(
                    'id'            => 'country',
                    'placeholder'   => esc_html__('Country' )
                ),
                array(
                    'id'            => 'city',
                    'placeholder'   => esc_html__('City' )
                ),
                array(
                    'id'            => 'district',
                    'placeholder'   => esc_html__('District' )
                ),
            )
        ),
		array (
            'id'         => 'gender',
            'name'       => 'gender',
            'title'      => esc_html__('Gender' ),
            'subtitle'   => esc_html__('Select your gender.' ),
            'placeholder'=> esc_html__('Gender' ),
            'type'       => 'select',
            'col'        => 12,
            'options'    => array('male'=>'Male','female'=>'Female'),
        ),
		array (
            'id'         => 'types',
            'title'      => esc_html__('Contract Type *' ),
            'subtitle'   => esc_html__('Select a job type' ),
            'notice'     => esc_html__('is required !'),
            'type'       => 'radio',
            'value'      => 2,
            'require'    => 1,
            'options'    => jb_get_type_options()
        ),
		array (
            'id'         => 'post_content',
            'title'      => esc_html__('Job Description *' ),
            'subtitle'   => esc_html__('Enter your job content.' ),
            'notice'     => esc_html__('is required !'),
            'type'       => 'textarea',
            'require'    => 1,
            'placeholder'=> esc_html__('Job description *' )
        ),
		array (
            'id'         => 'time',
            'title'      => esc_html__('Time' ),
            'subtitle'   => esc_html__('Enter your time.' ),
            //'notice'     => esc_html__('is required !'),
            'type'       => 'time',
            'require'    => 1,
            'placeholder'=> esc_html__('Select Time' )
        ),
		array (
            'id'         => 'slider',
            'title'      => esc_html__('Time' ),
            'subtitle'   => esc_html__('Enter your time.' ),
            //'notice'     => esc_html__('is required !'),
            'type'       => 'slider',
            'require'    => 1,
            'placeholder'=> esc_html__('Select Time' )
        ),
		
	);
	
	return $fields;
} 


//Remove theme employer add job form fields and set it as custom in template
remove_action('jobboard_form_post', 'jb_template_form_dynamic', 10);


/*create job category taxonomy*/
add_action('init','create_job_category',100);
function create_job_category(){
	$job_category_labels = array(
				'name'              => esc_html__( 'Categories' ),
				'singular_name'     => esc_html__( 'Category' ),
				'search_items'      => esc_html__( 'Search Categories' ),
				'all_items'         => esc_html__( 'All Categories' ),
				'parent_item'       => esc_html__( 'Parent Category' ),
				'parent_item_colon' => esc_html__( 'Parent Category:' ),
				'edit_item'         => esc_html__( 'Edit Category'),
				'update_item'       => esc_html__( 'Update Category' ),
				'add_new_item'      => esc_html__( 'Add New Category' ),
				'new_item_name'     => esc_html__( 'New Category' ),
				'menu_name'         => esc_html__( 'Categories' ),
			);

			$job_category = array(
				'hierarchical'       => true,
				'labels'             => $job_category_labels,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'query_var'          => true,
				'show_in_quick_edit' => false,
				'rewrite'            => array(
					'slug' => 'jobboard-category'
				),
				/* 'capabilities'       => array(
					'manage_terms' => 'manage_job_category_terms',
					'edit_terms'   => 'edit_job_category_terms',
					'delete_terms' => 'delete_job_category_terms',
					'assign_terms' => 'assign_job_category_terms'
				) */
			);
			
	register_taxonomy( 'jobboard-tax-categories', array( 'jobboard-post-jobs' ), $job_category );
	
	$job_board_labels = array(
				'name'              => esc_html__( 'Boards' ),
				'singular_name'     => esc_html__( 'Board' ),
				'search_items'      => esc_html__( 'Search Boards' ),
				'all_items'         => esc_html__( 'All Boards' ),
				'parent_item'       => esc_html__( 'Parent Board'),
				'parent_item_colon' => esc_html__( 'Parent Board:' ),
				'edit_item'         => esc_html__( 'Edit Board' ),
				'update_item'       => esc_html__( 'Update Board' ),
				'add_new_item'      => esc_html__( 'Add New Board' ),
				'new_item_name'     => esc_html__( 'New Board' ),
				'menu_name'         => esc_html__( 'Boards' ),
			);

			$job_board = array(
				'hierarchical'       => false,
				'labels'             => $job_board_labels,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'query_var'          => true,
				'show_in_quick_edit' => false,
				'rewrite'            => array(
					'slug' => 'jobboard-board'
				),
				/* 'capabilities'       => array(
					'manage_terms' => 'manage_job_category_terms',
					'edit_terms'   => 'edit_job_category_terms',
					'delete_terms' => 'delete_job_category_terms',
					'assign_terms' => 'assign_job_category_terms'
				) */
			);
			register_taxonomy( 'jobboard-tax-boards', array( 'jobboard-post-jobs' ), $job_board );
}
/*create job category taxonomy*/

/*Change dashboard page title*/
add_filter('jobboard_query_endpoint_new_title','change_dashboard_page_new_title');
function change_dashboard_page_new_title()
{
	return 'Create Job Request';
}
add_filter('jobboard_query_endpoint_jobs_title','change_dashboard_page_jobs_title');
function change_dashboard_page_jobs_title()
{
	return 'Request History';
}
/*Change dashboard page title*/


/*add additional field to specialism taxonomy*/
add_filter('jobboard_specialism_sections','add_category_for_specialism');
function add_category_for_specialism($sections){
	
	$category=array(
		'id'       => '_category',
		'type'     => 'select',
		'title'    => __('Select Category'), 
		'desc'     => __('Select category for this specialism.'),
		'data' => 'terms',
		'args' => array(
			'taxonomies' => array( 'jobboard-tax-categories' ),
			'hide_empty'=>false
		),
	);
	$sections['basic']['fields'][]=$category;
	return $sections;
	//echo '<pre>';print_r($sections['basic']['fields']);die;
}

/*add additional field to specialism taxonomy*/

/*Call speciality based on category on employer post job form*/
add_action( 'wp_ajax_speciality_on_category', 'speciality_on_category_func' );
add_action( 'wp_ajax_nopriv_speciality_on_category', 'speciality_on_category_func' );
function speciality_on_category_func(){
	$category=$_POST['category'];
	
	$output=array('msg'=>'','error'=>true);
	if(empty($category))
	{
		$output['msg']='Please select category';
		echo json_encode($output);
		die;
	}
	$args = array(
		'taxonomy'   => 'jobboard-tax-specialisms',
		'hide_empty' => false,
		'meta_query' => array(
			 array(
				'key'       => '_category',
				'value'     => $category,
				'compare'   => '='
			 )
		)
	);
	$specialities = get_terms($args);
	
	if(is_wp_error($specialities)){
		$output['msg']='No speciality found';
		echo json_encode($output);
		die;
	}
	
	$output['error']=false;
	
	$output['msg'].='<ul class="checkbox-style field-checkbox">';
				
			foreach($specialities as $speciality){
				
				$output['msg'].='<li><input id="speciality_'.$speciality->term_id.'" name="specialisms[]" class="checkbox" value="'.$speciality->term_id.'" type="checkbox">
				<label for="speciality_'.$speciality->term_id.'">
				'.$speciality->name.'</label>
				</li>';
			}			
		$output['msg'].='<br clear="all" />
			</ul>';
	
	echo json_encode($output);
	die;
}

/*Call speciality based on category on employer post job form*/