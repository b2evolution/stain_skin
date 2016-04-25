(function () {

    'use strict';

    var menu_sticky = function(){
        if ( $.fn.sticky ) {
            $(".main_navigation").sticky({
                topSpacing: 0,
                center:true
            });
        }
    }

    // Photo Index
    var PostGallery_masonry = function(){
        if( document.getElementById("grid") != null ){
            new AnimOnScroll( document.getElementById( 'grid' ), {
                minDuration : 0.4,
                maxDuration : 0.7,
                viewportFactor : 0.2
            } );
        }
    }

    var PostList_masonry = function(){
        if ( $.fn.masonry ) {
            $('.posts_list').masonry({
                // options
                itemSelector: '.evo_posts',
                percentPosition: true,
                //   columnWidth: 100
            });
        }
    }

    // Photo Index
    var PhotoIndex = function(){
        if( document.getElementById("post_gallery") != null ){
            new AnimOnScroll( document.getElementById( 'post_gallery' ), {
                minDuration : 0.4,
                maxDuration : 0.7,
                viewportFactor : 0.2
            } );
        }
    }

    // Document on Load
    $(function() {
        menu_sticky();
        PhotoIndex();
    });

    $(window).load(function() {
        PostGallery_masonry();
        PostList_masonry();
    })

}());
