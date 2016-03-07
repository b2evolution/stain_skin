jQuery( function($) {

   if ( $.fn.sticky ) {
      $(".main_navigation").sticky({
         topSpacing: 25,
         center:true
      });
   }

   if ( $.fn.masonry ) {
      $('.content_gallery').masonry({
        // options
        itemSelector: '.posts_list',
      //   columnWidth: 200
      });
   }

});
