/**
 * Creates the search index for Lunr.js to look through.
 * See official documentation here: 
 * https://lunrjs.com/guides/getting_started.html
 */
let idx;

(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.ebird_lunr = {
    attach: function (context, settings) {
      
      // Ensures that the script is run only once
      $(document, context).once('ebird_lunr').each(function() {
        
        // Fetches the eBird data from Drupal Settings, which was passed from
        // eBirdLunrSearch.php form
        let documents = drupalSettings.ebird_lunr.ebird_lunr_search.json_object;
        
        // Creates the Lunr search index
        idx = lunr(function () {
          this.ref('speciesCode')
          this.field('comName')
          this.field('locName')
        
          documents.forEach(function (doc) {
            this.add(doc)
          }, this)
        })
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
