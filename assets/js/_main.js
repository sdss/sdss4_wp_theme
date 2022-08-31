/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Roots = {
	// All pages
	common: {
		init: function() {
			// Scrollspy keeps menu on screen while scrolling 
			// - not really used b/x side menus are too long for many screens
			$('body').scrollspy({ target: '.sdss-docs-sidebar' });

			//Tocify - Generates a ToC for some context
			$(function() {
				if ($("#toc").length === 0) { return; }

				// Options are passed in as data with the #toc element
				var data = $( "#toc" ).data();
				
				//Calls the tocify method on your HTML div.
				//var toc = $("#toc").tocify( { selectors: data.selectors , context: data.context , scrollto: data.scrollto, extendpage: data.extendpage, showandhide: data.showandhide } );
				var toc = $("#toc").tocify().data("toc-tocify");
				toc.setOptions({ selectors: data.selectors , context: data.context , scrollTo: data.scrollto, extendPage: data.extendpage, showAndHide: data.showandhide, showAndHideOnScroll: data.showandhideonscroll });
				
				//console.log(toc.options);

			});
		}
	},
  // Home page
  home: {
    init: function() {
		// JavaScript to be fired on the home page
		$('.panner').kinetic();
		$('#left').click(function(){
			$('.panner').kinetic('start', { velocity: -10 });
		});
		$('#right').click(function(){
			$('.panner').kinetic('start', { velocity: 10 });
		});
		$('#end').click(function(){
			$('.panner').kinetic('end');
		});
		$('#stop').click(function(){
			$('.panner').kinetic('stop');
		});
		$('#detach').click(function(){
			$('.panner').kinetic('detach');
		});
		$('#attach').click(function(){
			$('.panner').kinetic('attach');
		});
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  },
  // Value Added Catalogs Page.
  value_added_catalogs: {
	
	init: function() {
		var DEBUG = false;

		$.extend($.expr[":"], {
			"icontains": function(elem, i, match, array) {
				return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
			}
		});
		
		var v = {
			artselector: 'article.vac.type-vac',
			checkboxclass: ( '.vaccheckbox' ),
			
			init: function( context ){
				
				var toggles = new v.Types( [] , [] , [] , [] );
				var headers = new v.Types( 'Has CAS' , 'Data Release' , 'Survey' , 'Object' );
				var toggle_container = $( '#vac-toggles' )[0];
				
				// Prevent form submitting
				$( toggle_container ).on( "submit" , "form#vacform" , v.prevent_default );
				
				// Add delegated Key Up event handler to update search results on page
				$( toggle_container ).on( "keyup" , "#vacsearch" , v.do_search );
				
				// Add delegated Key Up event handler to update # Shown
				$( toggle_container ).on( "click" , "#vacshowing" , v.do_showing );
				
				// Add delegated click event handler to checkboxes
				$( toggle_container ).on( "click" , v.checkboxclass , v.do_toggle );
				
				// Add delegated event handler to Reset button.
				$( toggle_container ).on( "click" , "#resetbtn" , v.do_reset );
				
				// Set Up Search and Toggles
				v.get_toggles( toggles );
				
				// Show VAC Filter-Search form
				v.show_form( 'vacform' , headers , toggles, toggle_container );
				
			} ,
						
			// For each toggle type, get names of filters
			show_form: function( id, hdrs, togs, container ){
				
				var thisHTML =
					'<form id="vacform">' +
					'<div class="row">' +
					'<div class="col-xs-12">' +
					v.show_status(  ) +
					v.show_search( 'Search' ) +
					'</div>';
					
				for ( var prop in hdrs) {
					thisHTML += '<div class="col-xs-12 ">';
					thisHTML += v.show_toggles( hdrs[ prop ] , 'vac'+prop , togs[ prop ] );
					thisHTML += '</div>';
				}

				thisHTML += '<div class="col-xs-12 "><input type="reset" name="resetbtn" id="resetbtn" value="Reset VAC Filters"></div>' +
				'</div>' +
				'</form>';
				
				container.innerHTML += thisHTML;

			} ,
									
			// Showing n of N results.
			show_status: function(  ){
				var numarts = $( v.artselector ).length;
				var thisHTML = "<div class='text-right vacstatus'><em>Showing <span id='vacshowing'>" + numarts + "</span> of " + numarts + " VAC<em></div>";
				return thisHTML;
			} ,
						
			// Showing search box
			show_search: function( heading ){
				var thisHTML = '<input type="text" id="vacsearch" name="vacsearch">';
				return "<div class='form-group form-group-vacsearch'><label class='h4' for='vacsearch'>" + heading + " </label><br>" + thisHTML + "</div>";
			} ,

			show_toggles: function( heading , slug , values ){
				var i=1;
				var thisHTML='';
				$(values).each( function () {
					thisHTML += '<input type="checkbox" class="vaccheckbox ' + slug + '" id="' + slug + i + '" name="' + slug + i + '" > <label for="' + slug + (i++) + '" >' + this + '</label><br>';
				});
				return "<h3 class='h4'>" + heading + "</h3><div class='form-group form-group-" + slug + "'>" + thisHTML + "</div>";
			} ,
			
			// For each toggle type, get names of filters
			get_toggles: function( t ){
				for ( var togtype in t ) {
					// get the unique names to filter by ( i.e. "DR13", "CAS", "STAR" )
					t[togtype]=v.unique('.vac-'+togtype);
					t[togtype].sort( );
				}
				t.dr.sort( function( a, b ){
					return ( Number( a.replace( /DR/ , "" ) ) - Number( b.replace( /DR/ , "" ) ) );
				} );
			} ,
			
			// Get and/or Set unique ids with basename base for all element selected with sel.
			set_ids: function( sel , base ){
				
				var n=1;
				var arr=[];
				base = v._default( base , 'base' );
				
				// if sel is a string and selects elements, then re-id those elements
				if ( ( sel.length > 0 ) && $( sel ).length > 0) {
					$( sel ).each( function() {
						if ( $(this).prop( "id" ).length === 0 ) {
							// give it a unique id if it doesn't have an id
							while( $( '#' + base + n ).length > 0 ) {
								n++;
							}
							$(this).prop( "id" , base + n++ );
						}
						arr.push( "#" + this.id );
					});
				}
				return arr;
			} ,

			// Update # articles shown
			do_showing: function( e ){
				$( e.currentTarget ).html( $( v.artselector + ":visible").length );
				//v._debug( $( e.currentTarget ).html() );
			} ,

			// Reset the Form and show all articles
			do_reset: function( e ){
				$('#vacform')[0].reset();
				$( v.artselector ).removeClass('hidden');
				$( "#vacshowing" ).trigger( "click" );
			} ,
			
			// Show only articles that contain text in search box (case insensitive)
			do_search: function( e ){
				if( e.keyCode === 13 ) { e.preventDefault(); }
				if( e.keyCode === 13 ) { e.stopPropagation(); }
				$( v.artselector ).addClass("hidden");
				$(".vac-article:icontains('" + e.currentTarget.value + "')" , v.artselector).parent().removeClass("hidden");
				$( "#vacshowing" ).trigger( "click" );
			} ,
			
			// Show only articles that match checked filter
			do_toggle: function( e ){
				
				toggles = new v.Types( [] , [] , [] , [] );
				v.get_toggles( toggles );
				var shown = [ v.set_ids( v.artselector , "vacart" ) ];
				
				// Loop through Filter Types
				$.each( toggles, function( t, tog ) {
					var checksel = v.checkboxclass + '.vac' + t;
					var thisarr=[];
					
					// If *all* checked, uncheck all.
					if ( $( checksel ).length === $( checksel + ':checked' ).length ) {
						$( checksel + ':checked' ).prop( 'checked' , false );
					}
					
					// One or more filter checked. 
					if ( $( checksel ).length  !== 0) {
					
						// Loop through Checked 
						$('.vaccheckbox.vac' + t + ':checked').each( function(){
							thisid=this.id;
							$( v.artselector + ' .vac-tag.vac-' + t).each( function(){
								if ( this.innerHTML === $('label[for="' + thisid + '"]' ).text() ) {
									thisarr.push( '#' + $(this).parents(v.artselector).prop('id') );
								}
							});
						});
							
						if ( thisarr.length !== 0 ) { shown.push( thisarr ); }
					}
				});
				
				var result = shown.shift().filter(function(v) {
					return shown.every(function(a) {
						return a.indexOf(v) !== -1;
					});
				});
				
				// Hide everything, then show checked
				$( v.artselector ).addClass('hidden');
				$( result.toString() ).removeClass('hidden');
				
				$( "#vacshowing" ).trigger( "click" );
				
			} ,
			
			prevent_default: function( e ){
				e.preventDefault();
			} ,
			
			unique: function( sel ){
				var arr=[];
				$(sel).each(function(){
					if ( arr.indexOf( $( this ).text(  ) ) <0 ) {
						arr.push( $( this ).text(  ) );
					}
				});
				return arr;
			} ,
			
			// For each toggle type, get names of filters
			Types: function( cas , dr , survey , object ) {
				this.cas=cas;
				this.dr=dr;
				this.survey=survey;
				this.object=object;
			} ,
						
			// Set default parameter
			_default: function( param , dflt ){
				return (param === undefined) ? dflt : param;
			} ,

			// Show DEBUG info
			_debug: function( param ){
				if ( !DEBUG ) {return;}
				switch ( typeof param ) {
					case 'function':
						console.log( JSON.stringify( param ) );
						break;
					case 'object':
					case 'array':
						console.log( param );
						break;
					default:
						console.log( param );
				}
				return;
			} ,
				
			// Swap positions of main and sidebar
			swap: function( x ) {
				if (x.matches) { // If media query matches
					$('aside.sidebar').insertBefore($('main.main'));
				} else {
					$('main.main').insertBefore($('aside.sidebar'));
				}
			}
		};
		
		// Ensure only one element with class .vac-container, then initialize it.
		if ( $('.vac-container').length === 1) {
			$( $('.vac-container')[0] ).prop("id" , "vac-container");
			v.init( "#vac-container" );}

		
		// Move Filterz to Top on small screen (768px)
		var x = window.matchMedia("(max-width: 768px)");
		v.swap(x); // Call listener function at run time
		x.addListener(v.swap); // Attach listener function on state changes
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
	fire: function(func, funcname, args) {
		var namespace = Roots;
		funcname = (funcname === undefined) ? 'init' : funcname;
		if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
			namespace[func][funcname](args);
		}
	},
	loadEvents: function() {
		UTIL.fire('common');
		$.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
			UTIL.fire(classnm);
		});
	}
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.



