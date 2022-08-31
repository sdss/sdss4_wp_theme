(function ($) {
     $(document).ready(function(){

          // EXTERNAL LINKS
          // Ensures all links with an external class open a new tab
          var $ext_links = $("li.external>a");
          $.merge($ext_links , $("a.external"));
          $($ext_links).each(function(){
               $(this).attr("target","_blank");
          });

          // KNOWN BROKEN LINKS
          // Add broken link class to known broken Domain Names.
          // var $dns = ["https://dr13.sdss.org" , "https://sas.sdss.org", "http://dr13.sdss.org" , "http://sas.sdss.org" ];
          // $dns.forEach( function( elem, indx, arr){
          //      $('a[href^="'+elem+'"]').addClass("broken_link");
          //      $('a[href^="'+elem+'"]').addClass("comingsoon");
          // });
          // 
          // This functionality worked with the Broken Link Checker: 
          // Dashboard: Settings: Link Checker: 
          //      [X] Apply custom formatting to broken links 
          //           .broken_link, a.broken_link {
          //                text-decoration: none;
          //                color: black;
          //                font-weight: bold;
          //                pointer-events: none;
          //                cursor: not-allowed;
          //           }
     });
})(jQuery);