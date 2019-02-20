/*
 *
 * SW_PARADISE_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function sw_paradise_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.sw-paradise-radio-img-'+labelclass).removeClass('sw-paradise-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('sw-paradise-radio-img-selected');
}//function