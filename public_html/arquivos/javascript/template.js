
function menuMobile(){
	$('body').toggleClass('menuMobile');
}

$('.menuPrincipal .parent .hasChild').click(function(){
	let li = $(this).parent();
	if($(li).hasClass('active')){
		$(li).removeClass('active');
		$(li).children('.nav-child').slideUp();
	}else{
		$(li).addClass('active');
		$(li).children('.nav-child').slideDown();
	}
});

$('.menuPrincipal .parent')
	.mouseenter(function() {
		if($(window).width() < 768) return;
		$(this).children('.nav-child').stop().slideDown();
	})
	.mouseleave(function() {
		if($(window).width() < 768) return;
		$(this).children('.nav-child').stop().slideUp();
	});

$(document).ready(function(){
	if($(window).width() >= 768) return;
	let li = $('.menuPrincipal .parent.active');
	if($(li).hasClass('active')){
		$(li).children('.nav-child').slideDown();
	}
	
});

$('.subs-menu-menu-mobile .menu-list-mobile .menu-item-mobile .menu-item-link-mobile').click(function(){
	let li = $(this).parent();
	if($(li).hasClass('item-mobile-ativo')){
		$(li).removeClass('item-mobile-ativo');
	}else{
		$(li).addClass('item-mobile-ativo');
	}
});

$('.subs-menu-menu-mobile .menu-bar-mobile .div-voltar').click(function(){
	let li = $(this).parent().parent();
	$(li).removeClass('item-mobile-ativo');
});

function number_format( numero, decimal, decimal_separador, milhar_separador ){
	numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+numero) ? 0 : +numero,
		prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
		sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
		dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix para IE: parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}	 
	return s.join(dec);
}

// lib iti: mover label abaixo do campo
$('.input-container .iti--allow-dropdown').each(function(){
	$(this).append($(this).next('label'));
})  

// placeholder shown
$(".input-container .campo-padrao, .input-container .campo-padrao2").on("input", function() {
	if ($(this).val() === "") {
		$(this).removeClass("not-placeholder-shown");
	} else {
		$(this).addClass("not-placeholder-shown");
	}
}).trigger("input");

// $(".animation").not('.animation-start-no').addClass('start');

/*function animationStart() {
	$('.animation').each(function(){
		$('.animation').each(function(){
			if($(this).hasClass('start') || $(this).hasClass('animation-start-no')) return;

			var posicao = $(this).offset().top;
			var scrollPosicao = $(window).scrollTop();
			var windowHeight = $(window).height();

			if (posicao < scrollPosicao + windowHeight - 100) {
				$(this).addClass('start');
			}
		});
	});
}

animationStart();

$(window).scroll(function() {
	animationStart();
});*/