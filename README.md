# Drupal eBird Lunr Search Module (in development)

## Introduction
This module provides Drupal sites the ability to search eBird data about local bird observations in your area!  

What is [eBird](www.ebird.org)?  It is a conservation project spearheaded by Cornell University that lets citizens across the world submit bird observations to track population density and migration data.  eBird graciously allows people access to this data, which this module takes advantage of.

I built it so that I could search local bird data for my Drupal website: [birdreston.com](www.birdreston.com).  You can see the module working at [www.birdreston.com/bird-search](www.birdreston.com/bird-search) . 

The module provides a search page that lets visitors look for any kind of bird seen in the Reston area in the last 30 days.  Additionally, a block is available that you can place on the front page of your website that has a form on it.  Visitors can use this block to search for birds, which then passes it along to the search page.

Finally, this module uses a fast, JavaScript library called Lunr to search the eBird data.  It is client side, meaning all the data is loaded on the user's browser.  There is no processing on the Drupal site, which means no need for a Solr external server.  All is done client side resulting in a fast search experience.

## How to use
* Install the module on your site, cloning this folder into the modules/custom directory.  Enable it under the Extend menu. Clear the website's cache.
* For this module to be used, you need an eBird API key.  If you do not have one, you may request one here: https://ebird.org/api/keygen. Afterwards, please save the API key to the module's configuration menu at the suburl, /admin/config/ebird_lunr/settings.
* Visit /bird-search, and there will now be a search page.  Fill out the form and click 'search'.  Results should now be rendered quickly to you about what local birds are in your area.
* There is also a custom block available to you called "eBird Lunr Search Teaser Block".  You can place this block on your homepage using the Layout Builder or Block Layout interface, which will help funnel visitors to your new search page.  

## To Dos
[ ] Provide options in the configuration menu that allows admins to change the birding hotspots searched by the module and how many results are returned.  Currently, it is hardcoded to certain birding hotspots in Reston.
[ ] Remove the committed node_modules folder and provide installation instructions with NPM or use online CDN.
