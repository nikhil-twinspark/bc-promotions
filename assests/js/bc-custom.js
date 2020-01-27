jQuery(document).ready(function(){
	// Date Picker
	var date = new Date();
    jQuery(".expiration_date .input-group.date > input").datepicker({
        changeMonth: true,
        changeYear: true,
        todayBtn: "linked",
        dateFormat: 'mm/dd/yy',
        minDate: 0,
        startDate: date,
        defaultDate: null
    }).on('change', function() {
        jQuery(this).valid();  // triggers the validation test
    });
	/*var date = new Date();
	date.setDate(date.getDate());
	jQuery('#expiration_date .input-group.date').datepicker({
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    forceParse: false,
	    calendarWeeks: false,
	    autoclose: true,
	    startDate: date
	});*/
	// Color Picker
	jQuery('.background-color').colorpicker();
    jQuery('.background-color').colorpicker().on('changeColor', function() {
        jQuery('.lazur-bg').css('background-color',this.value );
    });
	// Coupon Image Preview 
	function readURL(input) {
		if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	            jQuery('#image_upload_preview').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	jQuery("#promotion_coupon_image").change(function () {
	    readURL(this);
	});

});

// Add required for the active tab
jQuery(document).ready(function(){
	jQuery(".nav-tabs > li > a").click(function(){
		jQuery(this).tab('show');
	});
	jQuery('.nav-tabs > li > a').on('shown.bs.tab', function(event){
		var active = jQuery(event.target).attr('href'); // active tab
		// var previous = jQuery(event.relatedTarget).attr('href'); // previous tab
		if(active == '#tab-1'){
			jQuery('#promotion_title_metabox2').removeAttr('required');
			jQuery('#promotion_expiry_date_metabox2').removeAttr('required');
			jQuery('#promotion_coupon_image').removeAttr('required');
			jQuery('#tab_val2').val("false");

			jQuery('#promotion_title_metabox1').attr('required', 'required');
			jQuery('#promotion_color_metabox').attr('required', 'required');
			jQuery('#promotion_expiry_date_metabox1').attr('required', 'required');
			jQuery('#promotion_subheading').attr('required', 'required');
			jQuery('#promotion_footer_heading').attr('required', 'required');
			jQuery('#tab_val1').val("true");
			
			jQuery(".defaultmsg").remove("");
			jQuery(".wp-heading-inline").append("<b class='defaultmsg'> - Coupon Builder</b>");

		}else if(active == '#tab-2'){
			jQuery('#promotion_title_metabox1').removeAttr('required');
			jQuery('#promotion_color_metabox').removeAttr('required');
			jQuery('#promotion_expiry_date_metabox1').removeAttr('required');
			jQuery('#promotion_subheading').removeAttr('required');
			jQuery('#promotion_footer_heading').removeAttr('required')
			jQuery('#tab_val1').val("false");;

			jQuery('#promotion_title_metabox2').attr('required', 'required');
			jQuery('#promotion_expiry_date_metabox2').attr('required', 'required');
			// jQuery('#promotion_coupon_image').attr('required', 'required');
			jQuery('#tab_val2').val("true");
			
			jQuery(".defaultmsg").remove("");
			jQuery(".wp-heading-inline").append("<b class='defaultmsg'> - Custom Image</b>");
		}
	});
});


jQuery(document).ready(function() {
	var promotion_title = document.getElementById('promotion_title_metabox1');
	if(promotion_title === null) {
    	return 0;
    }
	promotion_title.onkeyup = function(){
	    document.getElementById('promotion_title').innerHTML = promotion_title.value;
	}
	var promotion_subheading = document.getElementById('promotion_subheading');
	promotion_subheading.onkeyup = function(){
	    document.getElementById('promotion_subheading_msg').innerHTML = promotion_subheading.value;
	}
	var promotion_footer_heading = document.getElementById('promotion_footer_heading');
	promotion_footer_heading.onkeyup = function(){
	    document.getElementById('promotion_footerheading_msg').innerHTML = promotion_footer_heading.value;
	}
	jQuery('#promotion_expiry_date_metabox1').on('change', function() {
	  jQuery('#expires1').html('Expires '+this.value);
	});
	jQuery(".wp-heading-inline").append("<b class='defaultmsg'> - Coupon Builder</b>");
	// Restrict html tags in inputbox
    jQuery(function(){
	    jQuery('#promotion_title_metabox1').on("keydown", function(e){
	        if (e.shiftKey && (e.which == 188 || e.which == 190)) {
	            e.preventDefault();
	        }
	    });
	});
});