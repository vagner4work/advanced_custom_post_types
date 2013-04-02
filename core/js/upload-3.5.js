jQuery(document).ready(function($) {

    function set_uploader(button, url, container, type) {
        $(button).click(function() {

            var send_attachment_bkp = wp.media.editor.send.attachment;

            wp.media.editor.send.attachment = function(props, attachment) {

                var placeholder = $(container).find('.image-placeholder img');
                if(placeholder.length > 0) {
                    $(placeholder).attr('src', attachment.url);
                } else {
                    $(container).find('.image-placeholder').append($('<img>').attr('src', attachment.url));
                }

                $(url).val(attachment.url);

                wp.media.editor.send.attachment = send_attachment_bkp;
            }

            wp.media.editor.open();

            return false;
        });
    }

    function set_send() {

    }

    // <a href="#" class="custom_media_upload">Upload</a>
    // <img class="custom_media_image" src="" />
    // <input class="custom_media_url" type="text" name="attachment_url" value="">
    // <input class="custom_media_id" type="text" name="attachment_id" value="">

    // $('.custom_media_upload').click(function() {

    //     var send_attachment_bkp = wp.media.editor.send.attachment;

    //     wp.media.editor.send.attachment = function(props, attachment) {

    //         $('.custom_media_image').attr('src', attachment.url);
    //         $('.custom_media_url').val(attachment.url);
    //         $('.custom_media_id').val(attachment.id);

    //         wp.media.editor.send.attachment = send_attachment_bkp;
    //     }

    //     wp.media.editor.open();

    //     return false;
    // });

    // place set_uploader functions below, button then field
    jQuery('.control-group').each(function(index, el) {
        var button = jQuery(el).find('.upload-button'), uploadUrl = jQuery(el).find('.upload-url'), type = null;
        if(jQuery(uploadUrl).hasClass('file')) {
            type = 'file';
        } else if(jQuery(uploadUrl).hasClass('image')) {
            type = 'image';
        }
        console.log(type);

        if(button){ set_uploader(button[0], uploadUrl[0], el, type);}

        jQuery(el).on('click', '.image-placeholder .remove-image', function(){
            jQuery(this).parent().parent().find('.upload-url').attr('value', '');
            jQuery(this).parent().remove();
            jQuery(el).append('<div class="image-placeholder"><div class="remove-image"></div></div>');
        });
    });

});