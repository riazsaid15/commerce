(function($) {
 Drupal.behaviors.myBehaviour = {
    attach: function (context, settings) {
      //console.log(settings.custom_date);
      function getTimeRemaining(endtime) {
		  var t = Date.parse(settings.custom_date) - Date.parse(new Date());
		  var seconds = Math.floor((t / 1000) % 60);
		  var minutes = Math.floor((t / 1000 / 60) % 60);
		  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		  var days = Math.floor(t / (1000 * 60 * 60 * 24));
		  return {
		    'total': t,
		    'days': days,
		    'hours': hours,
		    'minutes': minutes,
		    'seconds': seconds
		  };
		}
		function initializeClock(id, endtime) {
		  var clock = document.getElementById(id);
		  var daysSpan = clock.querySelector('.days');
		  var hoursSpan = clock.querySelector('.hours');
		  var minutesSpan = clock.querySelector('.minutes');
		  var secondsSpan = clock.querySelector('.seconds');
		  function updateClock() {
		    var t = getTimeRemaining(endtime);
		    daysSpan.innerHTML = t.days;
		    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
		    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
		    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
		    if (t.total <= 0) {
		      clearInterval(timeinterval);
		      document.getElementById("clockdiv").innerHTML = settings.custom_message_dateExpired;
		    }
		  }
		  updateClock();
		  var timeinterval = setInterval(updateClock, 1000);
		}
		var deadline = new Date(Date.parse(new Date()));
		initializeClock('clockdiv', deadline);
	  }
	};

	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
			BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
			iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
			Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
			Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
			any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	var contentWayPoint = function() {
		var i = 0;
		$('.animate-box').waypoint( function( direction ) {
			if( direction === 'down' && !$(this.element).hasClass('animated-fast') ) {
				i++;
				$(this.element).addClass('item-animate');
				setTimeout(function(){
					$('body .animate-box.item-animate').each(function(k){
						var el = $(this);
						setTimeout( function () {
							var effect = el.data('animate-effect');
							if ( effect === 'fadeIn') {
								el.addClass('fadeIn animated-fast');
							} else if ( effect === 'fadeInLeft') {
								el.addClass('fadeInLeft animated-fast');
							} else if ( effect === 'fadeInRight') {
								el.addClass('fadeInRight animated-fast');
							} else {
								el.addClass('fadeInUp animated-fast');
							}

							el.removeClass('item-animate');
						},  k * 100, 'easeInOutExpo' );
					});
				}, 100);	
			}
		} , { offset: '85%' } );
	};
	// Loading page
	var loaderPage = function() {
		$(".fh5co-loader").fadeOut("slow");
	};
	var screenHeight = function() {
		if ( $(window).width() > 768 && !isMobile.any() ) {
			$('.js-dt, .js-dtc').css('min-height', $(window).height());
		} else {
			$('.js-dt, .js-dtc').css('min-height', '');
		}
		$(window).resize(function(){
			if ( $(window).width() > 768 && !isMobile.any() ) {
				$('.js-dt, .js-dtc').css('min-height', $(window).height());
			} else {
				$('.js-dt, .js-dtc').css('min-height', '');
			}
		});	
	};
	var countDown = function() {
		var d = new Date(new Date().getTime() + 800 * 120 * 120 * 2000);
		simplyCountdown('.simply-countdown-one', {
			year: d.getFullYear(),
			month: d.getMonth() - 7,
			day: d.getDate()
		});
	};
	$(function(){
		contentWayPoint();
		loaderPage();
		screenHeight();
		countDown();
	});



})(jQuery);