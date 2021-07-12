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

  let registros = document.getElementsByClassName('registro').length;
  document.getElementById('quantidade-de-registros').innerHTML = `Total de links: ${registros}`;
});

function escolher(){
  let registros = document.getElementsByClassName('registro');
  let escolha = Math.floor(Math.random() * (registros.length - 0)) + 0;
  document.querySelector('#w-escolha-aleatoria').classList.add("p-2", "shadow-sm", "rounded", "border");
  document.getElementById('escolha-aleatoria').innerHTML = registros[escolha-1].innerHTML;
}