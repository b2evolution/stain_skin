jQuery(window).load(function (e) {

   if ( $.fn.sticky ) {
      $(".main_navigation").sticky({
         topSpacing: 0,
         center:true
      });
   }

   if ( $.fn.masonry ) {
      $('.posts_gallery').masonry({
        // options
        itemSelector: '.evo_post',
        percentPosition: true,
      //   columnWidth: 100
      });
   }

});
