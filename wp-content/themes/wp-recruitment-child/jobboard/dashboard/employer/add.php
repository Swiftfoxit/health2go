<?php
/**
 * The Template for displaying form add new job.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/dashboard/employer/new-job.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */
 


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wp_enqueue_style('zinput');
wp_enqueue_style('bootstrap-slider');
wp_enqueue_script('bootstrap-slider');
?>

<div style="padding:0 0 20px 0;">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d463876.939713476!2d46.542343215050785!3d24.72555534467799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e79fd5!2sRiyadh+Saudi+Arabia!5e0!3m2!1sen!2sin!4v1534855062932" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>

<form class="jobboard-form post-form" action="" method="post" enctype="multipart/form-data">

    
	
	<ul class="nav nav-tabs nav-justified">
		<li class="active"><a href="#tab-category" data-toggle="tab">Categories</a></li>
		<li><a href="#tab-speciality" data-toggle="tab">Speciality</a></li>
		<li><a href="#tab-filter" data-toggle="tab">Filter</a></li>
		<li><a href="#tab-time" data-toggle="tab">Time</a></li>
		<li><a href="#tab-cost" data-toggle="tab">Cost</a></li>
   </ul>
   <div class="tab-content form-fields">
	  <div id="tab-category" class="tab-pane fade in active">
	  <div class="row">
			<div class="field col-xs-12 col-sm-12 col-md-12 field-checkbox">
				
				 <div class="field-content check-list">
		<?php
			$categories = get_terms( 'jobboard-tax-categories', array(
				'hide_empty' => false,
				'parent'=>0
			) );
			if(!empty($categories))
			{
				?>
				<div class="zInputWrapper">
				<?php
				foreach($categories as $category){
					?>
					<div class="zInput" ><span style="display:table;width: 100%;height: 100%;"><span style="display: table-cell;vertical-align:middle;"><?php echo $category->name;?><input type="radio" name="category" title="<?php echo $category->name;?>" value="<?php echo $category->term_id;?>" style="display:none;"></span></span></div>
					<?php
				}
				?>
				</div>
				<?php
			}
			?>
			<!--sub category-->
			<?php
			if(!empty($categories))
			{
				foreach($categories as $category){
					$sub_categories = get_terms( 'jobboard-tax-categories', array(
						'hide_empty' => false,
						'parent'=>$category->term_id
					) );
					
					if(!empty($sub_categories))
					{ ?>
						<div class="zInputWrapper sub_cat" id="sub_cat_<?php echo $category->term_id;?>" style="display:none">
						<?php
						foreach($sub_categories as $sub_category){
							?>
					<div class="zInput" ><span style="display:table;width: 100%;height: 100%;"><span style="display: table-cell;vertical-align:middle;"><?php echo $sub_category->name;?><input type="radio" name="category" title="<?php echo $sub_category->name;?>" value="<?php echo $sub_category->term_id;?>" style="display:none;"></span></span></div>
					<?php
						}
						?>
						</div>
						<?php
					}
				}
			}
			?>
			<!--sub category-->
            
            <div class="btnn-bg"><a href="javascript:void(0);" onclick="toggle_nav('speciality');" class="btn btn-default btn-sm">Next</a></div>
            
			</div>
			
			
			
			</div>
			</div>
            
	  </div>
	  <div id="tab-speciality" class="tab-pane fade">
		<div class="row">
			<div class="field col-xs-12 col-sm-12 col-md-12 field-checkbox">
				
				 <div class="field-content check-list">

					<ul class="checkbox-style field-checkbox">
						<?php
						$specialities = get_terms( 'jobboard-tax-specialisms', array(
							'hide_empty' => false,
						) );
						if(!empty($specialities))
						{
							foreach($specialities as $speciality){
								?>
								<li><input id="speciality_<?php echo $speciality->term_id?>" name="specialisms[]" class="checkbox" value="<?php echo $speciality->term_id?>" type="checkbox">
								<label for="speciality_<?php echo $speciality->term_id?>">
								<?php echo $speciality->name?></label>
								</li>
								
								<?php
							}
						}
						?>
                    <br clear="all" />
					</ul>
                    
                    <div class="btnn-bg">                
                    <a href="javascript:void(0);" onclick="toggle_nav('category');" class="btn btn-default btn-sm">Previous</a>
                    <a href="javascript:void(0);" onclick="toggle_nav('filter');" class="btn btn-default btn-sm">Next</a>                
                    </div>
				</div>                
			</div>
		</div>
        
	  </div>
	  <div id="tab-filter" class="tab-pane fade">
      <div class="check-list2">
		<div class="row">
			<div class="field col-xs-2 col-sm-2 col-md-2">
				
				 <div class="field-content">
					<h5>Gender</h5>
					<ul class="radio-style field-radio">
							<li>
								<input id="radio-male" name="gender" class="radio" value="male" type="radio">
								<label for="radio-male">Male</label>
							</li>							
							<li>
								<input id="radio-female" name="gender" class="radio" value="female" type="radio">
								<label for="radio-female">Female</label>
							</li>

						</ul>
				 </div>
			</div>
			<div class="field col-xs-2 col-sm-2 col-md-2">				
				 <div class="field-content">
					<h5>Rating</h5>
                    
                    <ul class="checkbox-style field-checkbox">
                        <li><input id="location_27" name="locations[]" class="checkbox" value="27" type="checkbox">
                        <label class="str-5"></label>
                        </li>
                        <li><input id="location_27" name="locations[]" class="checkbox" value="27" type="checkbox">
                        <label class="str-4"></label>
                        </li>                        
                        <li><input id="location_27" name="locations[]" class="checkbox" value="27" type="checkbox">
                        <label class="str-3"></label>
                        </li>                        
                        <li><input id="location_27" name="locations[]" class="checkbox" value="27" type="checkbox">
                        <label class="str-2"></label>
                        </li>                        
                        <li><input id="location_27" name="locations[]" class="checkbox" value="27" type="checkbox">
                        <label class="str-1"></label>
                        </li>
					</ul>
					
				 </div>
			</div>
			<div class="field col-xs-2 col-sm-2 col-md-2 field-checkbox">				
				 <div class="field-content">
					<h5>Location</h5>
					<ul class="checkbox-style field-checkbox">
					<?php
						$locations = get_terms( 'jobboard-tax-locations', array(
							'hide_empty' => false,
							 'parent' => 0
						) );
						if(!empty($locations))
						{
							foreach($locations as $location){
								?>
								<li><input id="location_<?php echo $location->term_id?>" name="locations[]" class="checkbox" value="<?php echo $location->term_id?>" type="checkbox">
								<label for="location_<?php echo $location->term_id?>">
								<?php echo $location->name?></label>
								</li>
								
								<?php
							}
						}
						?>
						</ul>
				 </div>
			</div>
			<div class="field col-xs-2 col-sm-2 col-md-2 field-checkbox">				
				 <div class="field-content">
					<h5>Experience</h5>
					<ul class="checkbox-style field-checkbox">
						<li>
						<input id="experience_new" name="" class="checkbox" value="new" type="checkbox">
							<label for="experience_new">New</label>
						</li>
						<li>
						<input id="experience_experienced" name="" class="checkbox" value="experienced" type="checkbox">
							<label for="experience_experienced">Experienced</label>
						</li>
					</ul>
				 </div>
			</div>
			<div class="field col-xs-2 col-sm-2 col-md-2 field-checkbox">				
				 <div class="field-content">
					<h5>Board</h5>
					<ul class="checkbox-style field-checkbox">
					<?php
						$boards = get_terms( 'jobboard-tax-boards', array(
							'hide_empty' => false
						) );
						if(!empty($boards))
						{
							foreach($boards as $board){
								?>
								<li><input id="board_<?php echo $board->term_id?>" name="boards[]" class="checkbox" value="<?php echo $board->term_id?>" type="checkbox">
								<label for="board_<?php echo $board->term_id?>">
								<?php echo $board->name?></label>
								</li>
								
								<?php
							}
						}
						?>
						</ul>
				 </div>
			</div>
			<div class="field col-xs-2 col-sm-2 col-md-2 field-checkbox">				
				 <div class="field-content">
					<h5>Work permit</h5>
					<ul class="checkbox-style field-checkbox">
						<li>
						<input id="workpermit_local" name="" class="checkbox" value="local" type="checkbox">
							<label for="workpermit_local">Local</label>
						</li>
						<li>
						<input id="workpermit_expat" name="" class="checkbox" value="expat" type="checkbox">
							<label for="workpermit_expat">Expat</label>
						</li>
					</ul>
				 </div>
			</div>
		</div>
        
       			<div class="btnn-bg">                       
        		<a href="javascript:void(0);" onclick="toggle_nav('speciality');" class="btn btn-default btn-sm">Previous</a>
                <a href="javascript:void(0);" onclick="toggle_nav('time');" class="btn btn-default btn-sm">Next</a>
                </div>
	  </div>
      </div>
      
	  <div id="tab-time" class="tab-pane fade">
      
		<div class="row">
			<div class="field col-xs-12 col-sm-12 col-md-12">
				
				 <div class="field-content check-list">

					<h3>Time</h3>
                    
                    <div class="btnn-bg">                       
               
					<a href="javascript:void(0);" onclick="toggle_nav('filter');" class="btn btn-default btn-sm">Previous</a>
					<a href="javascript:void(0);" onclick="toggle_nav('cost');" class="btn btn-default btn-sm">Next</a>
					</div>
				</div>                
			</div>
		</div>
	  </div>
	  <div id="tab-cost" class="tab-pane fade">
      <div class="row">
		<div class="field col-xs-12 col-sm-12 col-md-12">				
			 <div class="field-content check-list">
			 <div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
				
				<input type="text" id="price_range" data-slider-id="price_range_slider" ata-slider-min="90" data-slider-max="380">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6"><?php echo function_exists('jb_get_currency_symbol')?jb_get_currency_symbol( jb_get_option( 'default-currency', 'USD' ) ):'';?>90/Hour<br/>MIN</div>
						<div class="col-xs-6 col-sm-6 col-md-6 text-right"><?php echo function_exists('jb_get_currency_symbol')?jb_get_currency_symbol( jb_get_option( 'default-currency', 'USD' ) ):'';?>380/Hour<br/>MAX</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 align-middle text-right">Add Your Offer</div>
						<div class="col-xs-6 col-sm-6 col-md-6 "><input type="text" id="proposed_price"></div>	
						<div class="col-xs-2 col-sm-2 col-md-2 text-left"><?php echo function_exists('jb_get_option')?jb_get_option( 'default-currency', 'USD' ).'/Hour':'';?></div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 align-middle text-right">Total Hours</div>
						<div class="col-xs-6 col-sm-6 col-md-6"><input type="text" id="total_hours"></div>
						<div class="col-xs-2 col-sm-2 col-md-2 text-left">Hour</div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 align-middle text-right">Total Service Request</div>
						<div class="col-xs-6 col-sm-6 col-md-6"><input type="text" id="total_price"></div>
						<div class="col-xs-2 col-sm-2 col-md-2 text-left"><?php echo function_exists('jb_get_option')?jb_get_option( 'default-currency', 'USD' ):'';?></div>
					</div>
				</div>
			 </div>
			
			 <div class="btnn-bg"><a href="javascript:void(0);" onclick="toggle_nav('time');" class="btn btn-default btn-sm">Previous</a></div>
			 </div>
		</div>      
      </div>       
      </div>
	</div>
<?php do_action("jobboard_form_post", $fields); ?>
</form>

<script type="text/javascript">
jQuery(document).ready(function($){
	
	$(document).on('click','.zInput',function(){
		$('.zInput').each(function(){
			$(this).removeClass('zSelected');
		});
		var elem=$(this).find('input[type="radio"]');
		elem.attr('checked','checked');
		$(this).addClass('zSelected');
		
		$('.sub_cat').not(elem.closest('.sub_cat')).hide();
		var val=elem.val();
		if($('#sub_cat_'+val).length)
			$('#sub_cat_'+val).show();
	});
	
	var price_slider=$("#price_range").slider({
		step: 1, 
		tooltip: 'always'
		}).on('slideStop',function(event){
			var newVal = $(this).data('slider').getValue();
			//tooltip handle
			price_slider_tooltip_text(newVal);
			
			$('#proposed_price').val(newVal).trigger('blur');
			
		}).on('change',function(event){
			var newVal = $(this).data('slider').getValue();
			//tooltip handle
			price_slider_tooltip_text(newVal);
		});
	//tooltip handle
	price_slider.trigger('change');	
	
	$(document).on('keydown','#total_hours,#proposed_price',function(e){
		var key   = e.keyCode ? e.keyCode : e.which;
    
		if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
			 (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
			 (key >= 35 && key <= 40) ||
			 (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
			 (key >= 96 && key <= 105)
		   )) e.preventDefault();
	});
	
	$(document).on('blur','#total_hours,#proposed_price',function(){
		var price=parseFloat($('#proposed_price').val());
		var hour=parseFloat($('#total_hours').val());
		
		if(isNaN(price) || isNaN(hour))
			return false;
		
		$('#total_price').val(price*hour);
	});
	 
	 function price_slider_tooltip_text(value){
		 $('#price_range_slider .tooltip .tooltip-inner').html(value+' <?php echo function_exists('jb_get_currency_symbol')?jb_get_currency_symbol( jb_get_option( 'default-currency', 'USD' ) ):'';?> / Hour');
	 }
});
function toggle_nav(item){
jQuery('.nav-tabs a[href="#tab-'+item+'"]').tab('show');
}
</script>