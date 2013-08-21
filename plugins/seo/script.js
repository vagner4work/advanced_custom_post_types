jQuery(document).ready(function($){
    var val = '',
        desc = '',
        orig_desc = $('#acpt-seo-preview-google-desc-orig').text(),
        orig_title = $('#acpt-seo-preview-google-title-orig').text();

    $('#acpt_acpt_seo_title').keyup(function(){
        val = $(this).val().substring(0, 59);
        var title = $('#acpt-seo-preview-google-title');
        title.text(val);
        console.log(orig_desc);
        if(val.length > 0) {
            title.text(val);
        } else {
            title.text(orig_title)
        }
    });

    $('#acpt_acpt_seo_description').keyup(function(){
        desc = $(this).val().substring(0, 156);
        if(desc.length > 0) {
            $('#acpt-seo-preview-google-desc').text(desc);
        } else {
            $('#acpt-seo-preview-google-desc').text(orig_desc)
        }
    });

    $('#acpt_acpt_seo_redirect_lock').click(function(e) {
        $($(this).attr('href')).removeAttr('readonly').focus();
        $(this).fadeOut();
        e.preventDefault()
    });


});