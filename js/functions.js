jQuery( document ).ready(function($) {
	/*------------------------------------------------------------------------------*/
	/* PrettyPhoto
	/*------------------------------------------------------------------------------*/
	jQuery("a[rel^='prettyPhoto']").prettyPhoto({changepicturecallback: onPictureChanged});
	//jQuery(".gallery-item .gallery-icon a").prettyPhoto();
});

function onPictureChanged() {

var twitterDiv = jQuery('.twitter');
twitterDiv.empty();

var fbDiv = jQuery('.facebook');

fbDiv.empty();

}
