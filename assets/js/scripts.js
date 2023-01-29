/**
 * JS for SeminarDesk Events Module
 */

(function ($) {
  
  $( document ).ready(function() {
    
    // Filter SD events, if parameter "term" or "q" is defined in url
    let mod_events = $('.sd-module.sd-events');
    if (mod_events.length > 0) {
      let url_params = new URL(document.location).searchParams;
      let are_search_terms_empty = true;
      let terms = '';
      if (url_params.has('q') || url_params.has('term')) {
        // Get search terms
        terms = url_params.has('q')?url_params.get('q'):url_params.get('term');
        terms = terms.trim().replace(',', ' ').toLowerCase().split(' ').filter(Boolean);
        are_search_terms_empty = (terms.length === 0);
      }
      // Search all events
      mod_events.find('.sd-event').each(function(){
        var matching = true;
        if (!are_search_terms_empty) {
          // Search events fields
          let searchable_text = $(this).find('.sd-event-title').text() + ' ' + $(this).find('.sd-event-facilitators').text() + ' ' + $(this).data('categories');
          matching = terms.every( 
            substring=>searchable_text.toLowerCase().includes( substring ) 
          );
        }
        // Show / hide events
        if (matching) {
          $(this).removeClass('hidden');
        }
        else {
          $(this).addClass('hidden');
        }
      });
      // Show / hide "not found" message
      console.log(mod_events.find('.sd-event:not(.hidden)'));
      if (mod_events.find('.sd-event:not(.hidden)').length > 0) {
        $('.no-events-found').addClass('hidden');
      }
      else {
        $('.no-events-found').removeClass('hidden');
      }
    }
  });
  
})(jQuery);