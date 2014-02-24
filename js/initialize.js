jQuery(function($) {
$('#twitter').sharrre({
  share: {
    twitter: true
  },
  template: '<a class="box" href="#"><div class="icon"></div><div class="shutter_frame"><div class="shutter"><div class="number count">{total}</div><div class="bar"></div><div class="text">Tweet</div></div></div></a>',
  enableHover: false,
  enableTracking: true,
  buttons: { twitter: {via: 'qutek_'}},
  click: function(api, options){
    api.simulateClick();
    api.openPopup('twitter');
  }
});
$('#facebook').sharrre({
  share: {
    facebook: true
  },
  template: '<a class="box" href="#"><div class="icon"></div><div class="shutter_frame"><div class="shutter"><div class="number count">{total}</div><div class="bar"></div><div class="text">Share</div></div></div></a>',
  enableHover: false,
  enableTracking: true,
  click: function(api, options){
    api.simulateClick();
    api.openPopup('facebook');
  }
});
$('#google').sharrre({
  share: {
    googlePlus: true
  },
  template: '<a class="box" href="#"><div class="icon"></div><div class="shutter_frame"><div class="shutter"><div class="number count">{total}</div><div class="bar"></div><div class="text">Share</div></div></div></a>',
  enableHover: false,
  enableTracking: true,
  click: function(api, options){
    api.simulateClick();
    api.openPopup('googlePlus');
  }
});

$('.social').hover(

function () {

    $(this).find('.shutter').stop(true, true).animate({
        bottom: '-36px'
    }, {
        duration: 300,
        easing: 'easeOutBounce'
    });
},

function () {
    $(this).find('.shutter').stop(true, true).animate({
        bottom: 0
    }, {
        duration: 300,
        easing: 'easeOutBounce'
    });
});  
});