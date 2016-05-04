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

    // Score Search Disp
    var Score_search = function() {
        var score_class = document.getElementsByClassName("search_result_score"), i, count;
        var len = score_class.length;

        /*
        * Resource
        * http://mcgivery.com/htmlelement-pseudostyle-settingmodifying-before-and-after-in-javascript/
        */
        var UID = {
            _current: 0,
            getNew: function(){
                this._current++;
                return this._current;
            }
        };

        HTMLElement.prototype.pseudoStyle = function(element,prop,value){
            var _this = this;
            var _sheetId = "pseudoStyles";
            var _head = document.head || document.getElementsByTagName('head')[0];
            var _sheet = document.getElementById(_sheetId) || document.createElement('style');
            _sheet.id = _sheetId;
            var className = "pseudoStyle" + UID.getNew();

            _this.className +=  " "+className;

            _sheet.innerHTML += "\n."+className+":"+element+"{"+prop+":"+value+" !important}";
            _head.appendChild(_sheet);
            return this;
        };

        for( i = 0, count = len; i < count; i++ ) {
            var get_score = score_class[i].innerHTML;
            // score_class[i].pseudoStyle( "after", "width", get_score );
            score_class[i].pseudoStyle( "after", "width", get_score );
        }
    }

    var waypoint = function() {
        new Waypoint({
            element: document.getElementById('content'),
            handler: function(direction) {
                Score_search();
                this.disable();
            },
            offset: '100px',
        });
    }

    var Slidebars = function() {

        if( document.getElementById("sb-site") != null ){
            new $.slidebars({
                siteClose: true, // true or false
                // disableOver: 480, // integer or false
                hideControlClasses: true, // true or false
                scrollLock: true // true or false
            });

        }

    }


    // Document on Load
    //////////////////////////////////////////////////
    $(function() {
        menu_sticky();
        PhotoIndex();
    });

    $(window).load(function() {
        PostGallery_masonry();
        PostList_masonry();
        waypoint();
        Slidebars();
    });

}(jQuery));
