
jQuery(document).ready(function($){
    var val = '',
        desc = '',
        orig_desc = $('#acpt-seo-preview-google-desc-orig').text(),
        orig_title = $('#acpt-seo-preview-google-title-orig').text();

    $('#acpt_acpt_seo_title').keyup(function(e){
        val = $(this).val().substring(0, 59);
        $('#acpt-seo-preview-google-title').text(val);
        console.log(orig_desc);
        if(val.length > 0) {
            $('#acpt-seo-preview-google-title').text(val);
        } else {
            $('#acpt-seo-preview-google-title').text(orig_title)
        }
    });

    $('#acpt_acpt_seo_description').keyup(function(e){
        desc = $(this).val().substring(0, 156);
        if(desc.length > 0) {
            $('#acpt-seo-preview-google-desc').text(desc);
        } else {
            $('#acpt-seo-preview-google-desc').text(orig_desc)
        }

    });
});