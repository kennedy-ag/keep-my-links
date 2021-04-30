jQuery(document).ready(function(){
  jQuery("#back-to-top").hide();
  jQuery('a#back-to-top').click(function(){
    jQuery('body,html').animate({
      scrollTop: 0
    }, 800);
    return false;
  });
  jQuery(window).scroll(function(){
    if(jQuery(this).scrollTop()>400){
      jQuery('#back-to-top').fadeIn();
    } else {
      jQuery('#back-to-top').fadeOut();
    }
  });
});