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
?>

<form class="jobboard-form post-form" action="" method="post" enctype="multipart/form-data">

    
	
	<ul class="nav nav-tabs">
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
				
				 <div class="field-content">
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
					{?>
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
			</div>
			
			
			
			</div>
			</div>
	  </div>
	  <div id="tab-speciality" class="tab-pane fade">
		<div class="row">
			<div class="field col-xs-12 col-sm-12 col-md-12 field-checkbox">
				
				 <div class="field-content">

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
					</ul>
				</div>
			</div>
		</div>
	  </div>
	  <div id="tab-filter" class="tab-pane fade">
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
					<select name="experience">
						<?php for($i=1;$i<=30;$i++){?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php } ?>
					</select>
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
													<li><input id="board_282" name="" class="checkbox" value="282" type="checkbox">
								<label for="board_282">
								Permit 1</label>
								</li>
								
																<li><input id="board_283" name="boards[]" class="checkbox" value="283" type="checkbox">
								<label for="board_283">
								Permit 2</label>
								</li>
								
																<li><input id="board_284" name="boards[]" class="checkbox" value="284" type="checkbox">
								<label for="board_284">
								Permit 2</label>
								</li>
								
														</ul>
				 </div>
			</div>
		</div>
	  </div>
	  <div id="tab-time" class="tab-pane fade">
		<h3>Menu 1</h3>
		<p>Some content in menu 1.</p>
	  </div>
	  <div id="tab-cost" class="tab-pane fade">
		<h3>Menu 1</h3>
		<p>Some content in menu 1.</p>
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
	 
});
</script>