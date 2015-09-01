(function($) {

    $(document).ready(documentReadyFunction);
    $(window).resize(windowResizeFunction);
    $(window).scroll(windowScrollFunction);
	
	var lastScrollTop = 0;
	var sidebarTopPosition = 0;
	var globalPadding = 20;
	var siteNavHeight = 50;
	var windowHeight = 0;
	var windowWidth = 0;
	var wrapMaxWidth = 960;
	var areGlobalVarsSet = false;
	var minHeightForFixedNav = 600;
	var isHeightTooSmallForFixedNav = false;

    function documentReadyFunction() {
        // functions for document ready
        onPageLoadOrResize();
        onPageLoad();
    }

    function windowResizeFunction() {
        onPageLoadOrResize();
    }

    function onPageLoad() {
	    twitterTimeline(true);
    }
	
    function onPageLoadOrResize () {
	    setGlobalVars();
  		topPaddingForFixedNavConpensation();
  		positionSidebar();
    }
    
    function windowScrollFunction() {
	    if(areGlobalVarsSet) {
		    positionSidebar();
		}	   
	}
    
    /* -----------------------------
	SUPPORT FUNCTIONS
	----------------------------- */
		function setGlobalVars() {
		    globalPadding = $("main").css('padding-bottom');
		    globalPadding = parseInt(globalPadding);		    
		    siteNavHeight = $("#site-navigation").outerHeight();		    
		    windowHeight = $(window).height();	
		    windowWidth = $(window).width();	    
		    areGlobalVarsSet = true;		    
		    
		    wrapMaxWidth = $(".wrap").css('max-width');
		    wrapMaxWidth = parseInt(wrapMaxWidth);
		    
		    minHeightForFixedNav = $('#less-vars').css('height');
		    minHeightForFixedNav = parseInt(minHeightForFixedNav);

		    if(windowHeight < minHeightForFixedNav) {
			    isHeightTooSmallForFixedNav = true;
			} else {
				isHeightTooSmallForFixedNav = false;
			}
			
			$('.sub-nav').hide();
		}
		
		function positionSidebar() {
			if(windowWidth >= wrapMaxWidth) {
			    var scroll = $(window).scrollTop();
			    var scrollBottom = scroll + windowHeight;
			    
			    var sidebarHeight = $("#sidebar-container").outerHeight();
			    var totalSidebarHeight = sidebarHeight + siteNavHeight + (globalPadding * 2);
			    
			    var sidebarPosition = $("#sidebar").offset();
			    var sidebarTop = sidebarPosition['top'];
			    
			    var fixedToBottomTopPosition = scrollBottom - sidebarTop - sidebarHeight - globalPadding; //Correct
			    var fixedToTopTopPosisiton = scroll - sidebarTop + siteNavHeight + globalPadding; //Correct
			    
			    var bottomGap = scrollBottom - sidebarTop - sidebarHeight - sidebarTopPosition - globalPadding; //Correct
			    var topGap = sidebarTopPosition - (scroll - sidebarTop) - siteNavHeight - globalPadding;
			    
			    if(isHeightTooSmallForFixedNav) {
				    topGap = topGap + siteNavHeight;
				    fixedToTopTopPosisiton = fixedToTopTopPosisiton - siteNavHeight;
				}
			        
			    if(fixedToTopTopPosisiton <= 0) {
					$("#sidebar").addClass('absolute-sidebar').removeClass('fixed-bottom-sidebar').removeClass('fixed-top-sidebar');
					$("#sidebar-container").css("top", 'auto').css("bottom", "auto");
					sidebarTopPosition = 0;
				} else if(totalSidebarHeight < windowHeight || topGap >= 0) {
					if(isHeightTooSmallForFixedNav) {
						var topOffset = globalPadding;
					} else {
						var topOffset = globalPadding + siteNavHeight;
					}
						
					$("#sidebar").removeClass('absolute-sidebar').removeClass('fixed-bottom-sidebar').addClass('fixed-top-sidebar');
					$("#sidebar-container").css("top", topOffset + "px").css("bottom", "auto");
					sidebarTopPosition = fixedToTopTopPosisiton;
				} else if((bottomGap >= 0 && scroll > lastScrollTop) || ($("#sidebar").hasClass("fixed-bottom-sidebar") && scroll > lastScrollTop)) {
					$("#sidebar").removeClass('absolute-sidebar').addClass('fixed-bottom-sidebar').removeClass('fixed-top-sidebar');
					$("#sidebar-container").css("top", 'auto').css("bottom", globalPadding + "px");
					sidebarTopPosition = fixedToBottomTopPosition;
				} else {
					$("#sidebar-container").css("top", sidebarTopPosition + 'px').css("bottom", "auto");
					$("#sidebar").addClass('absolute-sidebar').removeClass('fixed-bottom-sidebar').removeClass('fixed-top-sidebar');
				}
				
				lastScrollTop = scroll;
			}
		}
		
		function topPaddingForFixedNavConpensation() {
			var anchorHeight = siteNavHeight + globalPadding;
	  		$("main").css("padding-top", siteNavHeight);
	  		$(".anchor").css("top", -anchorHeight);
		}
		
		function twitterTimeline(callback) {
	    	var articleHeight = $("article").height();
	    	
	    	if (articleHeight > 2500 ) {
	    		articleHeight = 2500;
	    	}
	    	
	    	articleHeight = 1500;
	    	
	    	$(".twitter-timeline").height(articleHeight).attr("height", articleHeight);
	    	
	    	if(callback) {
	    		!function(d,s,id){
		    		var js,fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location)?'http':'https';
		    		
		    		if(!d.getElementById(id)) {
		    			js = d.createElement(s);
		    			js.id = id;
		    			js.src = p + "://platform.twitter.com/widgets.js";
		    			fjs.parentNode.insertBefore(js,fjs);
		    		}
		    	}(document,"script","twitter-wjs");
	    	}
	    }	

})(jQuery);