(function($) {
  'use strict';

  Drupal.animation = {
    'search_animation': function() {
      gsap.fromTo("tr", {autoAlpha: 0}, {autoAlpha: 1, duration: 1, stagger: .05});
    }
  };

})(jQuery);