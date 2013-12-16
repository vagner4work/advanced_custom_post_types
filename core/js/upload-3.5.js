jQuery(document).ready(function($) {

    function set_uploader(button, url, container, type) {
        $(button).click(function() {

            if(type == 'file') {
                var title = 'Select a File',
                    btnTitle = 'Use File',
                    typeInput = ''

                // If the media frame already exists, reopen it.
                if ( wp.media.frames.file_frame ) {
                    wp.media.frames.file_frame.open();
                    return;
                }
            } else {
                var title = 'Select an Image',
                    btnTitle = 'Use Image',
                    typeInput = 'image'

                // If the media frame already exists, reopen it.
                if( wp.media.frames.image_frame ) {
                    wp.media.frames.image_frame.open();
                    return;
                }
            }

            // Create the media frame.
            var temp_frame = wp.media({
                title: title,
                button: {
                    text: btnTitle
                },
                library: { type: typeInput },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // console.log(wp.media.frames);

            // When an image is selected, run a callback.
            temp_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                var attachment = temp_frame.state().get('selection').first().toJSON();


                  var placeholder = $(container).find('.image-placeholder img');
                  if(placeholder.length > 0) {
                    $(placeholder).attr('src', attachment.url);
                  } else {
                    $(container).find('.image-placeholder').append($('<img>').attr('src', attachment.url));
                  }

                  //console.log(attachment);
                  $(url).val(attachment.url);
                  $(container).find('.attachment-id-hidden').val(attachment.id);


            });

            //wp.media.editor.open();
            if(type == 'file') {
                wp.media.frames.file_frame = temp_frame;
                wp.media.frames.file_frame.open(); }
            else {
                wp.media.frames.image_frame = temp_frame;
                wp.media.frames.image_frame.open();
            }
            return false;
        });
    }

    function set_clearLink(clearLink) {
        clearLink.click(function(e) {
            $(this).siblings('.attachment-id-hidden, .upload-url').val('');
        });
    }

    // place set_uploader functions below, button then field
    $('.control-group').each(function(index, el) {
        var button = $(el).find('.upload-button'),
            uploadUrl = $(el).find('.upload-url'),
            type = null,
            clearLink = $(el).find('.clear-attachment');

        if($(uploadUrl).hasClass('file')) {
            type = 'file';
        } else if($(uploadUrl).hasClass('image')) {
            type = 'image';
        }

        if( clearLink.length > 0 ) { set_clearLink(clearLink) }
        if( button.length > 0 ){ set_uploader(button[0], uploadUrl[0], el, type);}

        $(el).on('click', '.image-placeholder .remove-image', function(){
            $(this).parent().parent().find('.upload-url, .attachment-id-hidden').attr('value', '');
            $(this).parent().remove();
            $(el).append('<div class="image-placeholder"><div class="remove-image">remove</div></div>');
        });
    });

    // image repeater field
    $('.image-repeater-field').each(function(index) {

      var
        field = $(this).data('name'),
        list = $(this).next(),
        el = $('<div class="add-image-repeater button" style="float: right">Add Image</div>');

      el.on('click', function(e){

        set_repeater_uploader(list, field);

      });

      $(this)
        .parent()
        .parent()
        .prepend(el);
    });

    $('.image-repeater-field + .list').sortable();
    $('.image-repeater-field + .list').on('click', 'b', function(){
      $(this).parent().remove();
    });

  function set_repeater_uploader(container, field) {

      var title = 'Select an Image',
        btnTitle = 'Use Image',
        typeInput = 'image'

      // If the media frame already exists, reopen it.
      if( wp.media.frames.image_frame ) {
        wp.media.frames.image_frame.open();
        return;
      }

      // Create the media frame.
      var temp_frame = wp.media({
        title: title,
        button: {
          text: btnTitle
        },
        library: { type: typeInput },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      // When an image is selected, run a callback.
      temp_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        var attachment = temp_frame.state().get('selection').first().toJSON();

        var data = '<input type="hidden" class="upload-id" name="'+field+'"/><div class="image-placeholder"></div><b>Remove</b>';

        var item =  $(document.createElement("li"))
          .append(data)
          .appendTo(container);

        var url = $(item).find('.upload-id');
        var placeholder = $(item).find('.image-placeholder');
        $(placeholder).append($('<img>').attr('src', attachment.sizes.thumbnail.url));

        //console.log(attachment);
        $(url).val(attachment.id);

      });

      //wp.media.editor.open();
      wp.media.frames.image_frame = temp_frame;
      wp.media.frames.image_frame.open();
      return false;
  }

});
