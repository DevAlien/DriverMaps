$(document).ready(function(){
	$('a[rel*=leanModal]').leanModal({ top : 200, closeButton: ".modal_close" });
	
	// FORM HIDDEN HEIGHT
	var formheight = $(window).height()-20-$('.fb-signup').height()-30;	
	
	$('#open-form-hidden').click(function() {
		$('#form-hidden').css("max-height", formheight + 'px');								  
	  	$('#form-hidden').slideDown('slow', function() {
			// Animation complete.
	  	});
	});
	$('#password').focus(function() {
	  $('#confirm-password').slideDown('slow', function() {
		// Animation complete.
	  });
	});
	
	// IF MAP EXIST
	if($('#map-canvas')) {
		// check viewport height
		var maxHeight = $(window).height();
		$('#map-canvas').height(maxHeight-150);
	}
	
	// Masonry
	/*
	var $container = $('#announce-container');
	$container.imagesLoaded(function(){
	  $container.masonry({
		itemSelector : '.item',
		isFitWidth : true,
		columnWidth : 420,
		/*
		isAnimated: true,
		animationOptions: {
			duration: 750,
    		easing: 'linear',
    		queue: false
		  }
		  
		  isAnimated: !Modernizr.csstransitions
	  });
	});
	*/
	
	$.Isotope.prototype._getCenteredMasonryColumns = function() {
    this.width = this.element.width();
    
    var parentWidth = this.element.parent().width();
    
                  // i.e. options.masonry && options.masonry.columnWidth
    var colW = this.options.masonry && this.options.masonry.columnWidth ||
                  // or use the size of the first item
                  this.$filteredAtoms.outerWidth(true) ||
                  // if there's no items, use size of container
                  parentWidth;
    
    var cols = Math.floor( parentWidth / colW );
    cols = Math.max( cols, 1 );

    // i.e. this.masonry.cols = ....
    this.masonry.cols = cols;
    // i.e. this.masonry.columnWidth = ...
    this.masonry.columnWidth = colW;
  };
  
  $.Isotope.prototype._masonryReset = function() {
    // layout-specific props
    this.masonry = {};
    // FIXME shouldn't have to call this again
    this._getCenteredMasonryColumns();
    var i = this.masonry.cols;
    this.masonry.colYs = [];
    while (i--) {
      this.masonry.colYs.push( 0 );
    }
  };

  $.Isotope.prototype._masonryResizeChanged = function() {
    var prevColCount = this.masonry.cols;
    // get updated colCount
    this._getCenteredMasonryColumns();
    return ( this.masonry.cols !== prevColCount );
  };
  
  $.Isotope.prototype._masonryGetContainerSize = function() {
    var unusedCols = 0,
        i = this.masonry.cols;
    // count unused columns
    while ( --i ) {
      if ( this.masonry.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    
    return {
          height : Math.max.apply( Math, this.masonry.colYs ),
          // fit container to columns that have been used;
          width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
        };
  };
	
	var $container = $('#announce-container');
	$container.isotope({
		itemSelector: '.item',
		masonry: {
			isFitWidth : true,
			columnWidth : 420,  
		  	isAnimated: !Modernizr.csstransitions
		}
	});
	
	var $optionSets = $('#filters .option-set'),
        $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
        }
        
        return false;
      });

	

});