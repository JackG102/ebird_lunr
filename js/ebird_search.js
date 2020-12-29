/**
 * The principal file that defines the logic for the search,
 * returns the results, and
 * renders it on the search page
 */

(function($, Drupal) {
  Drupal.behaviors.searchIndex = {
    attach: function (context, settings) {

      // Fetches URL parameter pass by teaser search box and executes search
      $(document).ready(function() {
        let searchParam = new URLSearchParams(window.location.search);
        let searchParamValues = searchParam.get('search');
        if (searchParam.has('search')) {
          $('#edit-search-box').val(searchParamValues);
          doSearch();
        }
      });

      // Executues search when 'Enter' key is pressed
      $('#edit-search-box').on('keypress',function(e) {
        if (e.which == 13) {
          doSearch();
        }
      });

      // When 'Search' button is pressed,
      // execute search function
      $('#lunr_search_button').once().on('click', doSearch); 
      
      // The function that searches the bird data and returns the results
      // in an HTML table partial
      function doSearch() {

        // Reset results visually
        $('#search_results').empty();

        // https://github.com/olivernn/lunr.js/issues/300
        // Next steps you take the reference, then you use the reference to
        // get the results from json object.  To do that you can use the
        // the filter method on the json object https://stackoverflow.com/questions/42143031/filter-json-by-key-value/42143070
        // then render the html from the returned results
        let results = idx.search($('#edit-search-box').val());

        // Creates array of the keys for species name
        // I can then filter json object by these keys and get results to render 
        let initial_search_results = results.map(function(item) { 
          return item.ref; 
        }) 

        let original_object = drupalSettings.ebird_lunr.ebird_lunr_search.json_object;
        let search_results;
        let final_results = [];

        // Returns the JSON object information to render on the page based 
        // on the returned ref field from the search
        // Janky code below runs a foreach loop with a filter that concatenates
        // arrays together -- probably super not in spirit of JS but it works for now
        initial_search_results.forEach(element => {
          search_results = original_object.filter(function(item) {
            return item.speciesCode == element;
          });
          final_results = final_results.concat(search_results); 
        });

        // Turns JSON object into a simple array with info I only need to render on page
        let bird_results_array = [];
        let individual_array = [];
        final_results.forEach(element => {
          
          // Builds array for each bird sighting
          individual_array.push(element.comName)
          individual_array.push(element.locName);
          individual_array.push(element.obsDt);
          individual_array.push(element.howMany);
          
          // Adds individual array to parent array that houses all sightings
          // Each sighting is 1 array
          bird_results_array.push(individual_array);
          
          // Reset array 
          individual_array = [];
        });

        // Build table results
        bird_results_array.forEach(element => {
          $('#search_results').append("<tr><td>" + element[0] + "</td><td>" + element[1] + "</td><td>" + element[2] + "</td><td>" + element[3] + "</td></tr>");
        });

        // Execute animation from search_animation.js file
        Drupal.animation.search_animation();
      };
    }
  }
})(jQuery, Drupal);


