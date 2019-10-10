<?php

/**
* Add the Claim Review Metabox to various post types
*
* @return void
*/
function claim_review_add_custom_box() {
	$screens    = array();
	$post_types = get_option( 'cr-post-types' );

	foreach ( $post_types as $key => $value ) {
		if ( $value ) {
			$screentoshow = str_replace( 'cr-showon', '', $key );
			$screens[] = $screentoshow;
		}
	}


	foreach ($screens as $screen) {
		add_meta_box(
			'claim_review_metabox',           // Unique ID
			__( 'Claim Review Schema', 'claimreview' ),  // Box title
			'claim_review_custom_box_html',  // Content callback, must be of type callable
			$screen,                   // Post type
			'normal',
			'high'
		);
	}
} add_action( 'add_meta_boxes', 'claim_review_add_custom_box' );



/**
* Function to add the cliam review meta data
*
* @param  object $post  The post object for the pag we're currently on
* @return void
*/
function claim_review_custom_box_html( $post ) {

	$claims = get_post_meta( $post->ID, '_fullfact_all_claims', true );
	$x      = 1;
	echo '<div class="allclaims-box">';
	wp_nonce_field( basename( __FILE__ ), 'claim_review_nonce' );
	if ( $claims ) {
		foreach ( $claims as $claim ) {

			$claimbox = claim_review_build_claim_box( $x, $claim );

			if ( $claimbox ) {
				echo $claimbox;
				$x++;
			}
		}
	}

	echo claim_review_build_claim_box( $x );

	echo '</div>';
	$x++;

	echo '<p class="cr-add-wrapper"><button class="cr-add-claim-field button button-primary" data-target="' . $x . '">' . __( 'Add a New Claim', 'claimreview' ) . '</button></p>';


}


/**
* Function to build the claim review box
*
* @param  integer $x     The number of the claim we're adding
* @param  mixed   $data  The data to be added.
* @return string         The claim review box
*/
function claim_review_build_claim_box( $x = 1, $data = false ) {
	$claimbox                = '';
	$claimreviewedpresent    = '';
	$claimdatecurrent        = '';
	$claimauthorcurrent      = '';
	$claimappearancecurrent  = array();
	$claimanchorcurrent      = '';
	$claimlocationcurrent    = '';
	$claimjobtitlecurrent    = '';
	$claimimagecurrent       = '';
	$claimnumericcurrent     = '';
	$claimratingimagecurrent = '';

	$max = get_option( 'cr-organisation-max-number-rating' );
	$min = get_option( 'cr-organisation-min-number-rating' );

	if ( is_numeric( $x ) ) {
		$arraykey = $x - 1;
	} else {
		$arraykey = $x;
	}

	if ( $data ) {
		$claimreviewedcurrent    = array_key_exists( 'claimreviewed', $data ) ? $data['claimreviewed'] : '';
		$claimdatecurrent        = array_key_exists( 'date', $data ) ? $data['date'] : '';
		$claimappearancecurrent  = array_key_exists( 'url', $data['appearance'] ) ? $data['appearance']['url'] : array();
		$claimoriginalcurrent    = array_key_exists( 'original', $data['appearance'] ) ? $data['appearance']['original'] : '';
		$claimauthorcurrent      = array_key_exists( 'author', $data ) ? $data['author'] : '';
		$claimasssessmentcurrent = array_key_exists( 'assessment', $data ) ? $data['assessment'] : '';
		$claimanchorcurrent      = array_key_exists( 'anchor', $data ) ? $data['anchor'] : '';
		$claimlocationcurrent    = array_key_exists( 'location', $data ) ? $data['location'] : '';
		$claimjobtitlecurrent    = array_key_exists( 'job-title', $data ) ? $data['job-title'] : '';
		$claimimagecurrent       = array_key_exists( 'image', $data ) ? $data['image'] : '';
		$claimnumericcurrent     = array_key_exists( 'numeric-rating', $data ) ? $data['numeric-rating'] : '';
		$claimratingimagecurrent = array_key_exists( 'rating-image', $data ) ? $data['rating-image'] : '';

		if ( $data && '' == $claimreviewedcurrent ) {
			return false;
		}
	}

	$claimbox .= '<div class="claimbox" id="claimbox' . $x . '" data-box="' . $x . '">';

	$claimbox .= '<h3>' . sprintf( __( 'Claim Review #%s', 'claimreview' ), $x ) . '</h3>';

	$claimbox .= '<div class="crfull"><label for="claim-reviewed-' . $x . '"><strong>' . __( 'Claim Reviewed', 'claimreview' ) . '</strong></label>
	<br />
	<textarea name="claim[' . $arraykey . '][claimreviewed]" id="claim-reviewed-' . $x . '" placeholder="" cols="90" rows="5" />' . $claimreviewedcurrent . '</textarea><br/>
	<span class="description">' . __( 'What the person or entity claimed to be true. Required by Google, Facebook &amp; Bing.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crhalf"><label for="claim-date-' . $x . '"><strong>' . __( 'Claim Date', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat crdatepicker" type="text" name="claim[' . $arraykey . '][date]" id="claim-date-' . $x . '" value="' . $claimdatecurrent . '" /><br/>
	<span class="description">' . __( 'When the person or entity made the claim.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crfull"><label for="claim-appearance-' . $x . '"><strong>' . __( 'Claim Appearance(s)', 'claimreview' ) . '</strong></label>
	<br /><span class="description">' . __( 'Url(s) for a document where this claim appears.', 'claimreview' ) . '
	<table class="claim-appearance">
	<tbody>';

	$firstrow = TRUE;

	foreach ( $claimappearancecurrent as $url ) {

		if ( filter_var( $url , FILTER_VALIDATE_URL) === FALSE ) {
			continue;
		}

		if ( $firstrow ) {
			$claimbox .= '<tr><td style="width:75%;"><input class="widefat" type="text" name="claim[' . $arraykey . '][appearance][url][]" id="claim-reviewed-' . $x . '" value="' . $url . '" placeholder="" /></td><td style="width:25%;""><input type="checkbox" name="claim[' . $arraykey . '][appearance][original]" id="claim-reviewed-' . $x . '" value="1" ' . checked( $claimoriginalcurrent, '1', false ) . '/>' . __( 'Original Appearance', 'claimreview' ) . '</td></tr>';
			$firstrow = FALSE;
		} else {
			$claimbox .= '<tr><td style="width:75%;"><input class="widefat" type="text" name="claim[' . $arraykey . '][appearance][url][]" value="' . $url . '" placeholder="" /></td><td style="width:25%;"><button class="button button-secondary cr-remove-row">Remove</button></td></tr>';
		}
	}

	if ( $firstrow ) {
		$claimbox .= '<tr><td style="width:75%;"><input class="widefat" type="text" name="claim[' . $arraykey . '][appearance][url][]" id="claim-reviewed-' . $x . '" value="" placeholder="" /></td><td style="width:25%;""><input type="checkbox" name="claim[' . $arraykey . '][appearance][original]' . $x . '" id="claim-reviewed-' . $x . '" value="1" ' . checked( $claimoriginalcurrent, '1', false ) . '/>' . __( 'Original Appearance', 'claimreview' ) . '</td></tr>';
	} else {
		$claimbox .= '<tr><td style="width:75%;"><input class="widefat" type="text" name="claim[' . $arraykey . '][appearance][url][]" value="" placeholder="" /></td><td style="width:25%;"><button class="button button-secondary cr-remove-row">Remove</button></td></tr>';
	}

	$claimbox .= '</tbody>
	</table>
	<a href="#" class="add-claim-appearance" data-arraykey="' . $arraykey . '">+' . __( 'Add another claim appearance', 'claimreview' ) . '</a></span></div>';

	$claimbox .= '<div class="crfull"><label for="claim-author-' . $x . '"><strong>' . __( 'Claim Author Name', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][author]" id="claim-author-' . $x . '" value="' . $claimauthorcurrent . '" /><br/>
	<span class="description">' . __( 'Name of the person or entity who made the claim.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crfull"><label for="claim-assesment-' . $x . '"><strong>' . __( 'Claim Assessment', 'claimreview' ) . '</strong></label>
	<br />
	<textarea name="claim[' . $arraykey . '][assessment]" id="claim-assesment-' . $x . '"  cols="90" rows="5" />' . $claimasssessmentcurrent . '</textarea>
	<br/><span class="description">' . __( 'Your written assessment of the claim. Required by Google, Facebook &amp; Bing.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<p><button class="claim-more-fields button button-secondary">' . __( 'More Fields', 'claimreview' ) . '</button></p>';

	$claimbox .= '<div class="claim-more-fields-box">';

	$claimbox .= '<div class="crfull"><label for="claim-review-anchor-' . $x . '"><strong>' . __( 'Claim Review Anchor', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][anchor]" id="claim-review-anchor-' . $x . '" value="' . $claimanchorcurrent . '" /><br/>
	<span class="description">' . __( 'If provided, this will be added to the end of the URL of the page.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crfull"><label for="claim-location-' . $x . '"><strong>' . __( 'Claim Location', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][location]" id="claim-location-' . $x . '" value="' . $claimlocationcurrent . '" /><br/>
	<span class="description">' . __( 'Where the claim was made.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crhalf"><label for="claim-author-job-title-' . $x . '"><strong>' . __( 'Claim Author Job Title', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][job-title]" id="claim-author-job-title-' . $x . '" value="' . $claimjobtitlecurrent . '" /><br/>
	<span class="description">' . __( 'Position of the person or entity making the claim.', 'claimreview' ) . '</span></div>';

	$claimbox .= '<div class="crhalf"><label for="claim-author-image-' . $x . '"><strong>' . __( 'Claim Author Image', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][image]" id="claim-author-image-' . $x . '" value="' . $claimimagecurrent . '" /><br/>
	<span class="description">' . __( 'Image URL of the person or entity making the claim.', 'claimreview' ) . '</span></div>';

	if ( -1 != $max && -1 != $min) {

		$claimbox .= '<div class="crhalf"><label for="claim-numeric-rating-' . $x . '"><strong>' . __( 'Numeric Rating', 'claimreview' ) . '</strong></label>
		<br />
		<input class="widefat" type="number" step="1" name="claim[' . $arraykey . '][numeric-rating]" id="claim-numeric-rating-' . $x . '" value="' . $claimnumericcurrent . '" max="' . $max . '" min="' . $min . '" /><br/>
		<span class="description">' . sprintf( __( 'A number rating for the claim. Between %s and %s', 'claimreview' ), $min, $max )  . '</span></div>';

	}

	$claimbox .= '<div class="crfull"><label for="claim-rating-image-' . $x . '"><strong>' . __( 'Claim Rating Image', 'claimreview' ) . '</strong></label>
	<br />
	<input class="widefat" type="text" name="claim[' . $arraykey . '][rating-image]" id="claim-rating-image-' . $x . '" value="' . $claimratingimagecurrent . '" /><br/>
	<span class="description">' . __( 'Image URL for the given rating.', 'claimreview' ) . '</span></div>';

	if ( $x != 1 ) {
		$claimbox .= '<div class="crfull cr-text-right"><button class="button button-secondary cr-remove-claim" data-remove-target="' . $x . '">'  . __( 'Remove Claim', 'claimreview' ) .  '</button></div>';
	}

	$claimbox .= '</div>';

	$claimbox .= '</div>';

	return $claimbox;
}


/**
* Helper function to get an arrow to put anywhere.
*
* @return string
*/
function claimbox_get_arrow() {
	return '<svg class="claim-review-arrow" width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><g><path fill="none" d="M0,0h24v24H0V0z"></path></g><g><path d="M7.41,8.59L12,13.17l4.59-4.58L18,10l-6,6l-6-6L7.41,8.59z"></path></g></svg>';
}


/**
* Save the metabox claim data
*
* @param  integer $post_id   The post ID we're looking at
* @param  object  $post      The post object we're using
* @return mixed              Usually the post ID
*/
function claimbox_save_data( $post_id, $post ) {

	/* if ( wp_verify_nonce( $_POST['claim_review_nonce'], basename( __FILE__ ) ) ) {
		$string = "nonce verified!";
	}  else {
		$string = "nonce not verified!";
	} */

	/* wp_die(
		print_r( $_POST, true ) .
		$string
	); */

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['claim_review_nonce'] ) || !wp_verify_nonce( $_POST['claim_review_nonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}




	$post_types = get_option( 'cr-post-types' );

	$post_type_string = 'cr-showon' . $post_type->name;

	$isinarray = FALSE;



	foreach ( $post_types as $key => $value ) {
		if ( $key == $post_type_string ) {
			$isinarray = TRUE;
			break;
		}
	}

	if ( ! $isinarray ) {
		return $post_id;
	}

	if ( array_key_exists( 'claim', $_POST ) ) {
		$newclaim = $_POST['claim'];
		array_values( $newclaim );
		update_post_meta( $post_id, '_fullfact_all_claims', $newclaim );
	}


} add_action( 'save_post', 'claimbox_save_data', 10, 2 );