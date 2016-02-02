/*
++++++++++++++++++++++++++++++++++++++++++++++++++++++
AUTHOR : R.Genesis.Art
PROJECT : R.Gen Landing Page (v0.20)
++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
var package_ver = 'v0.20';
var rgen = {};

/* HELPERS
********************************************/
rgen.elcheck = function(el) {
	'use strict';
	if ($(el).length > 0) {
		return true;
	} else {
		return false;
	};
}

rgen.uid = function(){
	'use strict';
	var uid = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	for(var i=0; i < 3; i++)
	uid += possible.charAt(Math.floor(Math.random() * possible.length));
	return 'rg'+uid;
	//return ("0000" + (Math.random()*Math.pow(36,4) << 0).toString(36)).slice(-4);
}

$.mediaquery({
	minWidth     : [ 200, 480, 600, 768, 992, 1200 ],
	maxWidth     : [ 1199, 991, 767, 599, 479 ],
	minHeight    : [ 400, 800 ],
	maxHeight    : [ 800, 400 ]
});

rgen.mobmenu = function(el) {
	'use strict';
	$(el).touch({
		tap: true
	}).on("tap", function(e) {
		var nav = $(this).attr('data-nav');
		if ($(nav).hasClass('open')) {
			$(nav).removeClass('open');
			$(this).find('.fa').removeClass('fa-times').addClass('fa-bars');
		} else {
			$(nav).addClass('open m-nav');
			$(this).find('.fa').removeClass('fa-bars').addClass('fa-times');
		};
	});
}

rgen.eqh = function(parentObj, childObj, a) {
	'use strict';
	if (rgen.elcheck(parentObj)) {
		$(parentObj).each(function(index, el) {
			if (a == "destroy") {
				$(this).equalize("destroy");
			} else {
				$(this).equalize({
					target: $(childObj)
				});
			};	
		});
	};
}

rgen.owlitems = function (arr) {
	'use strict';
	if (typeof(arr) == "string" && arr != 'false') {
		var t1 = arr.split('|');
		var t2 = {};
		$.each(t1, function(index, val) {
			var str = val;
			var newarr = str.split(',');
			t2[newarr[0]] = {}
			t2[newarr[0]] = {items: parseInt(newarr[1],10)};
		});
		return t2;
	}else if(arr === 'false'){
		return {};
	}else{
		return false;
	}
}

rgen.getvar = function (v, default_v, val_type) {
	'use strict';
	if (val_type == 'n') {
		return v ? parseInt(v,10) : default_v;
	} 
	if (val_type == 'b') {
		if (v == 'true') { return true; }
		else if (v == 'false') { return false; }
		else { return default_v; }
	}
	if (val_type == 's') {
		if (v == 'false') {
			return false;
		} else {
			return v ? v : default_v;
		};
		
	}
}

rgen.slider = function (owlObj) {
	
	'use strict';

	var resObj = {
		0    : { items:1 },
		420  : { items:2 },
		600  : { items:3 },
		768  : { items:3 },
		980  : { items:4 }
	}

	var owlEle = $(owlObj + ' .owl-carousel');

	var config = {
		center             : rgen.getvar($(owlObj).attr('data-center'), false, 'b'),
		stagePadding       : rgen.getvar($(owlObj).attr('data-stpd'), 0, 'n'),
		items              : rgen.getvar($(owlObj).attr('data-items'), 5, 'n'),
		margin             : rgen.getvar($(owlObj).attr('data-margin'), 0, 'n'),
		nav                : rgen.getvar($(owlObj).attr('data-nav'), false, 'b'),
		dots               : rgen.getvar($(owlObj).attr('data-pager'), false, 'b'),
		slideby            : rgen.getvar($(owlObj).attr('data-slideby'), 1, 'n'),
		rbase              : rgen.getvar($(owlObj).attr('data-rbase'), $(owlObj).parent(), 's'),
		res                : $(owlObj).attr('data-itemrange') ? rgen.owlitems($(owlObj).attr('data-itemrange')) : resObj,
		animOut            : rgen.getvar($(owlObj).attr('data-out'), 'fadeOut', 's'),
		animIn             : rgen.getvar($(owlObj).attr('data-in'), 'fadeIn', 's'),
		autoplay           : rgen.getvar($(owlObj).attr('data-autoplay'), false, 'b'),
		autoplayTimeout    : rgen.getvar($(owlObj).attr('data-timeout'), 3000, 'n'),
		autoplayHoverPause : rgen.getvar($(owlObj).attr('data-hstop'), true, 'b'),
		loop               : rgen.getvar($(owlObj).attr('data-loop'), false, 'b'),
		autoWidth          : rgen.getvar($(owlObj).attr('data-awidth'), false, 'b'),
		autoHeight         : rgen.getvar($(owlObj).attr('data-hauto'), true, 'b'),
		touchDrag          : rgen.getvar($(owlObj).attr('data-tdrag'), true, 'b'),
		mouseDrag          : rgen.getvar($(owlObj).attr('data-mdrag'), true, 'b'),
		pullDrag           : rgen.getvar($(owlObj).attr('data-pdrag'), true, 'b'),
		contentHeight      : rgen.getvar($(owlObj).attr('data-h'), true, 'b')
	}
	$(owlObj).animate({opacity:1}, 300, function(){

		 owlEle.owlCarousel({
			center                : config.center,
			stagePadding          : config.stagePadding,
			items                 : config.items,
			margin                : config.margin,
			nav                   : config.nav,
			dots                  : config.dots,
			slideBy               : config.slideby,
			navText               : ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
			responsiveBaseElement : config.rbase,
			responsive            : config.res,
			loop                  : $(owlObj+" .owl-carousel > .item").length > 1 ? config.loop : false,
			animateOut            : config.animOut, //'slideOutDown',
			animateIn             : config.animIn, //'flipInX',
			autoplay              : config.autoplay,
			autoplayTimeout       : config.autoplayTimeout,
			autoplayHoverPause    : config.autoplayHoverPause,
			autoHeight            : config.autoHeight,
			autoWidth             : config.autoWidth,
			touchDrag             : config.touchDrag,
			mouseDrag             : config.mouseDrag,
			pullDrag              : config.pullDrag,
			autoplaySpeed : 2000,

			onInitialized: function () {
				owlEle.animate({opacity: 1}, 300);
				if (owlEle.find('.feedback-box1').length > 0) {
					config.contentHeight ? rgen.eqh(owlEle, ".feedback-box1", "") : false;
				}
				if (owlEle.find('.feedback-box3').length > 0) {
					config.contentHeight ? rgen.eqh(owlEle, ".feedback-box3 .feedback", "") : false;
				}
				if (owlEle.find('.feedback-box4').length > 0) {
					config.contentHeight ? rgen.eqh(owlEle, ".feedback-box4 .feedback", "") : false;
				}
			}
		});

		$(owlObj).find('.carousel-btn .prev').on('click', function() { owlEle.trigger('prev.owl.carousel'); });
		$(owlObj).find('.carousel-btn .next').on('click', function() { owlEle.trigger('next.owl.carousel'); });

	});
}

rgen.fullwh = function (obj) {
	'use strict';
	// global vars
	var winWidth = $(window).width();
	var winHeight = $(window).height();
	// set initial div height / width
	$(obj).css({
		'width': winWidth,
		'height': winHeight,
	});
}
rgen.fullh = function (obj) {
	'use strict';
	// global vars
	var winHeight = $(window).height();
	// set initial div height / width
	$(obj).css({
		'height': winHeight,
	});
}


rgen.swiper_slider = function (obj) {
	
	'use strict';

	var config = {
		autoplay : rgen.getvar($(obj).attr('data-autoplay'), 1000, 'n'),
		speed : rgen.getvar($(obj).attr('data-speed'), 3000, 'n'),
		fullsize : rgen.getvar($(obj).attr('data-fullsize'), false, 'b'),
	}

	if (config.fullsize) {
		rgen.fullwh(obj);
		$(window).resize(function(){
		rgen.fullwh(obj);
		});
	};

	var swiper = new Swiper(obj, {

		direction: 'horizontal',
		touchEventsTarget: 'container',
		speed: config.speed,
		autoplay: config.autoplay,
		autoplayDisableOnInteraction: true,
		effect: 'fade', // 'slide' or 'fade' or 'cube' or 'coverflow'
		parallax: false,
		pagination: obj+' .swiper-pagination',
		paginationClickable: true,
		nextButton: obj+' .swiper-button-next',
		prevButton: obj+' .swiper-button-prev',
		onInit: function (swiper) {
			$(obj).animate({opacity: 1}, 300);
		}
	});
}

rgen.swiper_gallery = function (obj) {
	var galleryTop = new Swiper(obj+' .gallery-top', {
		nextButton: obj+' .swiper-button-next',
		prevButton: obj+' .swiper-button-prev',
		spaceBetween: 0,
		onInit: function (swiper) {
			$(obj).animate({opacity: 1}, 300);
			$(obj+' .gallery-top .swiper-slide').each(function(index, el) {
				if ($(this).find('.caption').length > 0) {
					if ($(this).find('.overlay').length == 0) {
						$(this).find('.caption').after('<b class="full-wh overlay"></b>');	
					};
				} else {
					$(this).find('.overlay').remove();
				};
			});
		}
	});
	var galleryThumbs = new Swiper(obj+' .gallery-thumbs', {
		spaceBetween: 10,
		centeredSlides: true,
		slidesPerView: 'auto',
		touchRatio: 0.2,
		slideToClickedSlide: true
	});
	galleryTop.params.control = galleryThumbs;
	galleryThumbs.params.control = galleryTop;
}


rgen.blur = function (obj) {
	'use strict';
	var $blurEl = $(obj.container);
	$blurEl.backgroundBlur({
		imageURL : obj.img/*images[0]*/,
		blurAmount : 20,
		imageClass : 'bg-blur',
		overlayClass : 'bg-blur-overlay',
		duration: 500,
		endOpacity : 1
	});
}
rgen.tabs = function(obj) {
	'use strict';

	if ($(obj.tb).hasClass('tabs-auto')) {
		var t = 0;
		$(obj.tb).find('.tb-list > .tb').each(function(){
			var tb = obj.count+'-tb-'+t;
			$(this).attr("data-tb", '#'+tb);
			$(obj.tb).find('.tb-content > .tb-pn:eq('+t+')').attr("id", tb);
			t++;
		});

		$(obj.tb).on('click', '.tb-list > .tb', function (e) {
			e.preventDefault();
			
			$(this).closest('.tb-list').find('.tb').removeClass('active');
			$(this).addClass('active');

			var target = $(this).attr('data-tb');
			$(target).siblings('.tb-pn').removeClass('active');
			$(target).addClass('active');
			
		});
		if ($(obj.tb).find('.tb-list > .tb').hasClass('active')) {
			$(obj.tb).find('.tb-list > .tb.active').click();
		} else {
			$(obj.tb).find('.tb-list > .tb:first').click();	
		};

	} else {
		$('[data-tb]').each(function(index, el) {
			var target = $(this).attr('data-tb');
			$(target).addClass('tab-pn');
		});
		$(obj).on('click', function (e) {
			e.preventDefault();
			
			$(obj).closest('.tab-widget').find('[data-tb]').removeClass('active');
			$(this).addClass('active');

			var target = $(this).attr('data-tb');
			$(target).siblings('.tab-pn').hide();
			$(target).show().addClass('active');
			
		}).eq(0).click();
	};
	
}

rgen.global_validation = {
	form: '',
	rules: { 
		email            : { required: true, email: true },
		name             : { required: true },
		message          : { required: true },
		phone            : { required: true, number: true },
		date             : { required: true, date: true },
		people           : { required: true, number: true },
		datetime         : { required: true, date: true },

		pickup_location  : { required: true },
		pickup_datetime  : { required: true, date: true },
		dropoff_datetime : { required: true, date: true },
		dropoff_location : { required: true },
	},
	msgpos: 'normal',
	msg: {
		email: {email: "Please, enter a valid email"}
	},
	subscribe_successMsg : "You are in list. We will inform you as soon as we finish.",
	form_successMsg : "Thank you for contact us. We will contact you as soon as possible.",
	
	successMsg : "",
	errorMsg   : "Oops! Looks like something went wrong. Please try again later."
}

rgen.formVaidate = function (obj) {
	'use strict';
	var msgpos = $(obj.form).attr('data-msgpos') ? $(obj.form).attr('data-msgpos') : 'normal';
	if (msgpos == 'append') {
		$(obj.form).validate({
			onfocusout: false,
			onkeyup: false,
			rules: obj.rules,
			messages: obj.msg,
			highlight: false,
			errorPlacement: function(error, element) {
				if (msgpos == 'append') {
					error.appendTo( element.closest("form").find('.msg-wrp'));
				};
			},
			success: function(element) {
				element.remove();
			}
		});
	} else {
		$(obj.form).validate({
			onfocusout: false,
			onkeyup: false,
			rules: obj.rules,
			messages: obj.msg,
			highlight: false,
			success: function(element) {
				element.remove();
			}
		});
	};
}

rgen.resetForm = function (form) {
	'use strict';
	$(form).find('input[type="text"], input[type="email"], textarea').val(null);
}

rgen.contactForm = function($form, formData, validate_data){
	'use strict';

	if ($form.find('label.error').length > 0) { $form.find('label.error').hide(); }
	
	var $btn = $form.find(".btn").button('loading');

	if ($form.valid()) {
		$.ajax({
			url: $form.attr('action'),
			type: 'POST',
			data: formData,
			success: function(data) {
				if (data.status == 'error') {
					// Email subscription error messages
					swal("Error!", data.type, "error");
					$btn.button('reset');
					rgen.resetForm($form);
				} else {
					swal("Success!", validate_data.successMsg, "success");
					$btn.button('reset');
					$.magnificPopup.close();
					rgen.resetForm($form);
					setTimeout(function() { swal.close(); }, 4000);
				};
			},
			error: function() {
				swal("Error!", validate_data.errorMsg, "error");
				$btn.button('reset');
				$.magnificPopup.close();
				setTimeout(function() { swal.close(); }, 4000);
			}
		});
	} else {
		$form.find("label.error").delay(4000).fadeOut('400', function() {
			$(this).remove();
		});
		$btn.button('reset');
	};
}

rgen.formWidget = function (obj) {
	'use strict';

	var config = {
		popup_selector : $(obj).attr('data-popup') ? '.'+$(obj).attr('data-popup') : false,
		form_type      : $(obj).attr('data-formtype') ? $(obj).attr('data-formtype') : 'normal',
		form_selector  : obj
	}

	var $form = $(config.form_selector);

	// Validation rules
	rgen.global_validation.form = config.form_selector;
	var validate_data = rgen.global_validation;

	// Pop up form
	if (config.popup_selector) {
		$(config.popup_selector).each(function(index, el) {
			$(this).magnificPopup({
				type: 'inline',
				preloader: false
			});
		});
	};

	// Date picker
	if ($form.find(".date-pick").length > 0) {
		$form.find(".date-pick").each(function(index, el) {
			$(this).datepicker({
				clearBtn: true,
				todayHighlight: true,
				autoclose: true
			});		
		});
	};

	// Date time picker
	if ($form.find(".datetime-pick").length > 0) {
		$form.find(".datetime-pick").each(function(index, el) {
			$(this).datetimepicker();
		});
	};

	// Form validation
	rgen.formVaidate(validate_data);

	// Form
	$form.find('button').off('click').on('click', function(e) {
		e.preventDefault();
		if (config.form_type == "newsletter") {
			rgen.global_validation.successMsg = rgen.global_validation.subscribe_successMsg;
		} else {
			rgen.global_validation.successMsg = rgen.global_validation.form_successMsg;
		};

		rgen.contactForm($form, $form.serializeObject(), validate_data);
		return false;
	});
}

$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		
		// Field labels
		var field_label = $('[name='+this.name+']').attr('data-label') ? $('[name='+this.name+']').attr('data-label') : this.name;

		// Field values
		if (o[this.name]) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push({val: this.value, label: field_label} || '');
		} else {
			//o[this.name] = this.value || '';
			o[this.name] = {val: this.value, label: field_label} || '';
		}
	});
	return o;
};

rgen.videoBg = function (obj, imglist) {
	'use strict';
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

	if( isMobile.any() ){
		$(obj).css("display","none");
		/*$(obj).vegas({
			slides: [
				{ src: "images/bg-1.jpg" },
				{ src: "images/bg-2.jpg" },
				{ src: "images/bg-3.jpg" },
				{ src: "images/bg-4.jpg" }
			]
			slides: imglist
		});*/
	}
	else{
		$(obj).css("display","block");
		$(obj).YTPlayer();
	}
}
rgen.videoPopup = function(obj) {
	'use strict';
	$(obj).magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});
};

rgen.inlinePopup = function (obj) {
	'use strict';
	$('body').off('click').on('click', obj, function(e) {
		$(this).magnificPopup({
			type: 'inline',
			preloader: false
		}).click();	
	});
}

rgen.bgSlider = function (setting) {
	'use strict';
	setTimeout(function () {
		$(setting.obj).vegas({
			delay: setting.delay,
			slides: setting.slides,
			animation: setting.effect
		});	
	}, 1000);
	
}

rgen.linkscroll = function (obj) {
	'use strict';
	$(document).on('click', obj, function(e) {
		e.preventDefault();
		if ($(this).closest('.nav-links').hasClass('nav-links') == false && $(this).attr('href').indexOf("popup") === -1) {
			// target element id
			var id = $(this).attr('href');
			// target element
			var $id = $(id);
			if ($id.length === 0) {	return;	}
			// top position relative to the document
			var pos = $(id).offset().top;
			// animated top scrolling
			$('body, html').animate({scrollTop: pos}, 1200);
		};
	});
}

rgen.countdown = function (obj) {
	'use strict';

	var config = {
		day   : parseInt($(obj).attr("data-day"),10),
		month : parseInt($(obj).attr("data-month"),10),
		year  : parseInt($(obj).attr("data-year"),10),
		hour  : parseInt($(obj).attr("data-hr"),10),
		min   : parseInt($(obj).attr("data-min"),10),
		sec   : parseInt($(obj).attr("data-sec"),10)
	}
	
	var oneDay     = 24*60*60*1000; // hours*minutes*seconds*milliseconds
	var firstDate  = new Date(config.year, config.month-1, config.day-1);
	var d          = new Date();
	var secondDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());
	var diffDays   = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
	
	var countdownHtml  = '<div class="inner-dashboard">';
		countdownHtml += '	<!-- DAYS -->';
		countdownHtml += '	<div class="dash days_dash">';
		countdownHtml += '		<div class="inner-dash">';
		countdownHtml += diffDays > 99 ? '<div class="digit">0</div>' : '';
		//countdownHtml += '<div class="digit">0</div>';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '		</div>';
		countdownHtml += '		<span class="dash_title">days</span>';
		countdownHtml += '	</div>';
		countdownHtml += '	<!-- HOURS -->';
		countdownHtml += '	<div class="dash hours_dash">';
		countdownHtml += '		<div class="inner-dash">';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '		</div>';
		countdownHtml += '		<span class="dash_title">hours</span>';
		countdownHtml += '	</div>';
		countdownHtml += '	<!-- MINIUTES -->';
		countdownHtml += '	<div class="dash minutes_dash">';
		countdownHtml += '		<div class="inner-dash">';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '		</div>';
		countdownHtml += '		<span class="dash_title">minutes</span>';
		countdownHtml += '	</div>';
		countdownHtml += '	<!-- SECONDS -->';
		countdownHtml += '	<div class="dash seconds_dash">';
		countdownHtml += '		<div class="inner-dash">';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '			<div class="digit">0</div>';
		countdownHtml += '		</div>';
		countdownHtml += '		<span class="dash_title">seconds</span>';
		countdownHtml += '	</div>';
		countdownHtml += '</div>';

	$(obj).html(countdownHtml);

	// DESKTOP CLOCK
	$(obj).countDown({
		targetDate: {
			'day': 		config.day,
			'month': 	config.month,
			'year': 	config.year,
			'hour': 	config.hour,
			'min': 		config.min,
			'sec': 		config.sec
		},
		omitWeeks: true
	});
}

;(function(){
	'use strict';

	jQuery(document).ready(function($) {

		$('html').before('<!-- '+package_ver+' -->');

		$('[data-toggle="tooltip"]').tooltip({
			container: 'body'
		});

		/* NAVIGATION
		********************************************/
		if (rgen.elcheck(".nav-links")) {
			rgen.mobmenu('.nav-handle');
			$('.nav-links a').smoothScroll({
				speed: 1200,
				offset: $('.nav-wrp').attr('data-sticky') == 'y' ? -($('.nav-wrp').height()-20) : 0,
				beforeScroll: function() {
					$('.nav-links a').removeClass('active');
					$('.nav-handle').trigger('tap');
				},
				afterScroll: function() {
					$(this).addClass('active');
				}
			});
		} else {
			rgen.mobmenu('.nav-handle');
		};

		/* LINK SCROLL
		********************************************/
		if (rgen.elcheck("#page[data-linkscroll='y']")) {
			rgen.linkscroll('a[href^="#"]:not(.nav-links)');
		};

		/* All navigation utilities
		********************************************/
		if (rgen.elcheck(".nav-wrp")) {

			var $nav = $(".nav-wrp");

			$nav.attr('data-glass') === 'y' ? $nav.addClass('bg-glass') : null;
			$nav.attr('data-above') === 'y' ? $nav.addClass('show-above') : null;

			if ($nav.attr('data-sticky') == 'y') {
				$nav.addClass('navbar-fixed-top').removeClass('show-above');
				$(window).scroll(function () {
					if ($(window).scrollTop() > $("nav").height()) {
						$nav.addClass("nav-sticky");
						$nav.attr('data-glass') === 'y' ? $nav.removeClass('bg-glass') : null;
						
					} else {
						$nav.removeClass("nav-sticky");
						$nav.attr('data-glass') === 'y' ? $nav.addClass('bg-glass') : null;
					}
				});
			};
			
			if ($nav.attr('data-hide') == 'y') {
				$nav.addClass('nav-hide');
				$(window).scroll(function () {
					if ($(window).scrollTop() > $("nav").height()) {
						$nav.addClass("nav-show");
					} else {
						$nav.removeClass("nav-show");
					}
				});	
			};
		}

		/* Apply ID on each sections
		********************************************/
		if (rgen.elcheck(".main-container section")) {
			$(".main-container section").each(function(index, el) {
				$(this).attr('id', rgen.uid());
			});
		}

		/* Apply full screen section
		********************************************/
		if (rgen.elcheck("[data-fullwh='y']")) {
			$("[data-fullwh='y']").each(function(index, el) {
				rgen.fullwh(this);
				var fullwhSection = this;
				$(window).resize(function(){
					rgen.fullwh(fullwhSection);
				});
			});
		}
		if (rgen.elcheck("[data-fullh='y']")) {
			$("[data-fullh='y']").each(function(index, el) {
				rgen.fullh(this);
				var fullhSection = this;
				$(window).resize(function(){
					rgen.fullh(fullhSection);
				});
			});
		}

		/* Apply background image
		********************************************/
		if (rgen.elcheck("[data-bg]")) {
			$("[data-bg]").each(function(index, el) {
				$(this).css({backgroundImage: "url("+$(this).attr("data-bg")+")"});
			});
		}

		/* Parallax background image
		********************************************/
		if (rgen.elcheck("[data-stellar='y']")) {
			$("[data-stellar]").each(function(index, el) {
				if (!$(this).attr("data-stellar-background-ratio")) {
					$(this).attr('data-stellar-background-ratio', '0.5');	
				}
			});
			$.stellar({
				horizontalScrolling: false,
				verticalOffset: 0
			});
		}


		/* Video popup
		********************************************/
		if (rgen.elcheck(".video-popup")) {
			$(".video-popup").each(function(index, el) {
				rgen.videoPopup(this);
			});
		}

		/* Normal popup
		********************************************/
		if (rgen.elcheck(".set-popup")) {
			$(".set-popup").each(function(index, el) {
				$(this).magnificPopup({
					type: 'inline',
					preloader: false
				});
			});
		}

		/* Count box
		********************************************/
		if (rgen.elcheck(".count-box")) {
			$('.count-box .count').counterUp();
		};

		/* Tab widget
		********************************************/
		if (rgen.elcheck(".tab-widget")) {
			$(".tab-widget").each(function(index, el) {
				var obj = $(this).find('[data-tb]');
				rgen.tabs(obj);
			});
		}

		if (rgen.elcheck(".tabs-auto")) {
			$(".tabs-auto").each(function(index, el) {
				var tabObj = {
					count: index,
					tb: this
				}
				rgen.tabs(tabObj);
			});
		}

		
		/* Carousel widget
		********************************************/
		if (rgen.elcheck(".carousel-widget")) {
			var carousel = 0;
			$('.carousel-widget').each(function(){

				// SET ID ON ALL OBJECTS
				carousel++;
				var owlObj = 'owl'+carousel;
				$(this).css({opacity:0});
				$(this).attr("id", owlObj);
				$(this).addClass(owlObj);
				rgen.slider("#"+owlObj);
			});
		}

		/* Swiper widget
		********************************************/
		if (rgen.elcheck(".swiper-widget")) {
			var swiperWid = 0;
			$('.swiper-widget').each(function(){

				// SET ID ON ALL OBJECTS
				swiperWid++;
				var swiObj = 'swiper'+swiperWid;
				$(this).css({opacity:0});
				$(this).attr("id", swiObj);
				$(this).addClass(swiObj);
				rgen.swiper_slider("#"+swiObj);
			});
		}
		// Swiper gallery mode
		if (rgen.elcheck(".swiper-gallery")) {
			var swiperGallery = 0;
			$('.swiper-gallery').each(function(){

				// SET ID ON ALL OBJECTS
				swiperGallery++;
				var swiGal = 'swiperGallery'+swiperGallery;
				$(this).css({opacity:0});
				$(this).attr("id", swiGal);
				$(this).addClass(swiGal);
				rgen.swiper_gallery("#"+swiGal);
			});
		}
		

		/* Set blur background image
		********************************************/
		if (rgen.elcheck("[data-blurimg]")) {
			$("[data-blurimg]").each(function(index, el) {
				var blurObj = {
					container: $(this),
					img: $(this).attr('data-blurimg')
				}
				rgen.blur(blurObj);
			});
		}

		/* video background
		********************************************/
		if (rgen.elcheck(".videobg")) {
			$(".videobg").each(function(index, el) {
				rgen.videoBg(el);
			});
		};
		
		
		/* Other section 1 script
		********************************************/
		if (rgen.elcheck('.other-section-1')) {
			$('.other-section-1').each(function(){
				var $el = $(this);
				var img = $el.find('.r img');
				$el.find('ol > li').click(function(e){
					
					e.preventDefault();
					
					var src = $(this).attr('data-img');
					$(this).parent().find('.active').removeClass('active');
					$(this).addClass('active');
					
					img.css({opacity: 0, marginTop: -20});
					img.attr('src', src);
					img.stop().animate({
						opacity: 1,
						marginTop: 0},
						500, function() {
					});
				}).eq(0).click();
			});
		}

		/* Simple pop up gallery
		********************************************/
		if (rgen.elcheck(".popgallery-widget")) {
			var magnific = 0;
			$('.popgallery-widget').each(function(){

				magnific++;
				var obj = 'popgallery'+magnific;
				$(this).attr("id", obj);
				$(this).addClass(obj);

				$('#'+obj).magnificPopup({
					delegate: '.pop-img',
					type: 'image',
					tLoading: 'Loading image #%curr%...',
					mainClass: 'mfp-img-mobile',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1] // Will preload 0 - before current, and 1 after the current image
					},
					image: {
						tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
						titleSrc: function(item) {
							return item.el.attr('title');
						}
					}
				});
			});
		}

		/* Background slider
		********************************************/
		if (rgen.elcheck("[data-bgslider]")) {
			$("[data-bgslider]").each(function(index, el) {
				var s1 = $(this).attr('data-bgslider');
				var s2 = s1.split('|');
				var bgslides = [];
				$.each(s2, function(index, val) {
					bgslides.push({ src: val });
				});
				var bgslideSetting = {
					obj: this,
					delay: 6000,
					slides: bgslides,
					animation: 'kenburns'
				}
				rgen.bgSlider(bgslideSetting);
			});
		};

		/* Countdown
		********************************************/
		if (rgen.elcheck(".countdown-widget")) {
			var countdown = 0;
			$(".countdown-widget").each(function(index, el) {
				var obj = 'countdown'+countdown;
				$(this).children('div').attr("id", obj);
				rgen.countdown("#"+obj);
				countdown++;
			});
		}

		
		/* Notify form
		********************************************/
		if (rgen.elcheck("#subscribe")) {
			var $subscribeForm = $('#subscribe');
			var subscribe_validate_data = {
				form: "#subscribe",
				rules: { email: { required: true, email: true } },
				msg: {
						email: {
							required: "Please enter email before submit.",
							email: "Please, enter a valid email"
						}
					},
				msgpos: 'append',
				successMsg: "<div class='msg-success'>Congrats! You are in list. We will inform you as soon as we finish.</div>",
				errorMsg: "<div class='msg-error>Oops! Looks like something went wrong. Please try again later.</div>"
			}
			rgen.formVaidate(subscribe_validate_data);
			$('#subscribe').off('click').on('click', '#submit', function(e) {
				e.preventDefault();
				var formData = {
					email: $subscribeForm.find('input').val()
				}
				rgen.contactForm($subscribeForm, formData, subscribe_validate_data);
				return false;
			});
		}

		/* Form widget
		********************************************/
		if (rgen.elcheck(".form-widget")) {
			$(".form-widget").each(function(index, el) {
				rgen.formWidget(this);
			});
		};


		/* RESPONSIVE
		********************************************/
		$.mediaquery("bind", "mq-key", "(min-width: 992px)", {
			enter: function() {
				rgen.eqh(".eqh", ".eqh > div", "");
				rgen.eqh(".feature-section-3", ".info", "");
				rgen.eqh(".feature-section-5 .eqh", ".eqh > div", "destroy");
				if (!rgen.elcheck(".testimonial-section .carousel-widget")) {
					rgen.eqh(".testimonial-section", ".feedback-box1", "");
				}
			},
			leave: function() {
				rgen.eqh(".eqh", ".eqh > div", "destroy");
				rgen.eqh(".feature-section-3", ".info", "destroy");
			}
		});

		$.mediaquery("bind", "mq-key", "(min-width: 200px) and (max-width: 991px)", {
			enter: function() {
				$('.nav-transparent').removeClass('nav-transparent');

				if (rgen.elcheck(".content-section-8")) {
					$('.content-section-8 .bg-section').appendTo('.content-section-8 .l');	
				}
				$(".nav-wrp").removeClass('show-above').removeClass('bg-glass');
			},
			leave: function() {
				if (rgen.elcheck(".content-section-8")) {
					$('.content-section-8 .bg-section').appendTo('.content-section-8');
				}

				$('.nav-wrp').attr('data-glass') === 'y' ? $('.nav-wrp').addClass('bg-glass') : null;
				$('.nav-wrp').attr('data-above') === 'y' ? $('.nav-wrp').addClass('show-above') : null;
			}
		});

/* end ======================*/
	});
})();