jQuery(document).ready(function($){
  $('.toggle-btn').click('click', function(e) {
    e.preventDefault()
    $('.toggle-profile').toggle();
  });

  $('.author-list').on('click', 'tr td', function(e){
    e.stopPropagation();
    window.location = $(this).parent().find('a').attr('href');
  })

  $(".fancybox").fancybox();

});