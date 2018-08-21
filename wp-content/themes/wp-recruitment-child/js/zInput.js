jQuery.fn.zInput = function($){

var $inputs = this.find(":radio,:checkbox");
$inputs.hide();
var inputNames = [];
$inputs.map(function(){
  inputNames.push(jQuery(this).attr('name'));
});

inputNames = jQuery.unique(inputNames);

jQuery.each(inputNames, function(index,value){

	var $element = jQuery("input[name='" + value + "']");
	var elementType = $element.attr("type");
	$element.wrapAll('<div class="zInputWrapper" />');
	if (elementType == "radio"){
		$element.wrap(function(){ return '<div class="zInput"><span style="display:table;width: 100%;height: 100%;"><span style="display: table-cell;vertical-align:middle;">' + jQuery(this).attr("title") + '</span></span></div>'});
	}
	if (elementType == "checkbox")
	{
		$element.wrap(function(){ return '<div class="zInput zCheckbox"><span style="display:table;width: 100%;height: 100%;"><span style="display: table-cell;vertical-align:middle;">' + jQuery(this).attr("title") + '</span></span></div>'});	
	}
	
	});


var $zRadio = jQuery(".zInput").not(".zCheckbox");
var $zCheckbox	= jQuery(".zCheckbox");

$zRadio.click(function(){
	$theClickedButton = jQuery(this);

	//move up the DOM to the .zRadioWrapper and then select children. Remove .zSelected from all .zRadio
	$theClickedButton.parent().children().removeClass("zSelected");
	$theClickedButton.addClass("zSelected");
	$theClickedButton.find(":radio").prop("checked", true).change();	
	});

$zCheckbox.click(function(){
	$theClickedButton = $(this);

	//move up the DOM to the .zRadioWrapper and then select children. Remove .zSelected from all .zRadio
	$theClickedButton.toggleClass("zSelected");
	$theClickedButton.find(':checkbox').each(function () { this.checked = !this.checked; $(this).change()});
	});	
	
  
  jQuery.each($inputs,function(k,v){
    if(jQuery(v).attr('checked')){
      
      jQuery(v).parent().click();
      
    }
    
  });
  
}

