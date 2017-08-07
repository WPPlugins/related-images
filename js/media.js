jQuery(document).ready(function($){


	/**
	 * remove related image
	 */
	$(document.body).on('click', 'a.remove-related-image', function(){

		var id = $(this).attr('id');

		$('#tr_' + id).css({ backgroundColor: '#fbe986' }).fadeOut(500, function () {
			$(this).remove();
		});

		return false;

	});


	/**
	 * add related image
	 */
	$(document.body).on('click', '.ri-open-media', function(){

		var ri_media_frame;

		// re-open if frame exists
		if ( ri_media_frame ) {
			ri_media_frame.open();
			return;
		}

		// config the media manager
		ri_media_frame = wp.media.frames.ri_media_frame = wp.media({
			className: 'media-frame ri-media-frame',
			frame: 'select',
			multiple: true,
			title: ri_media.title,
			library: {
				type: 'image'
			},
			button: {
				text:  ri_media.button
			}
		});

		// attach 'select' event to catch data and send back to backend
		ri_media_frame.on('select', function(){

			var items = [], select, markup;
			var media_attachment = ri_media_frame.state().get('selection').toJSON();

			// loop all images
			$.each(media_attachment, function(key, obj) {

				// get pre-defined positions
				var positions_obj = $.parseJSON(ri_media.positions);

				// build markup
				select = '<select name="related-images-select[]">';
				$.each(positions_obj, function(key, object) {
					select += '<option value="' + obj.id + '_' + key + '">' + key + '</option>'
				});
				+ '</select>';

				markup = '<tr id="tr_image_' + obj.id + '">'
				+ '<td>'
				+ '<img src="' + obj.url + '" width="30" height="auto">'
				+ '</td>'
				+ '<td>' + select + '</td>'
				+ '<td><a href="#" id="image_' + obj.id + '" class="remove-related-image">remove</a></td>'
				+ '</tr>';

				items.push(markup);

			});

			$('#related-list').append( items.join('') );

		});

		// open up the frame.
		ri_media_frame.open();

		return false;

	});

});