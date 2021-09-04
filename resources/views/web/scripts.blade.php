<!-- Javascript -->
 <script src="{{ asset('js/app.js') }}"></script>	
<script type="text/javascript" src="{{ asset('web/javascript/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/tether.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/jquery-validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/easing.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/jquery.fancybox.js') }}"></script>
	<script type="text/javascript" src="{{ asset('web/javascript/kinetic.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/jquery.flexslider-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/owl.carousel.js') }}"></script>
 
<script type="text/javascript" src="{{ asset('web/javascript/parallax.js') }}"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtRmXKclfDp20TvfQnpgXSDPjut14x5wk&region=GB"></script>
	<script type="text/javascript" src="{{ asset('web/javascript/gmap3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('web/javascript/jquery.final-countdown.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/javascript/jquery.cookie.js') }}"></script>

<script type="text/javascript" src="{{ asset('web/javascript/main.js') }}"></script>

<!-- Revolution Slider -->
<script type="text/javascript" src="{{ asset('web/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/slider.js') }}"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->    
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.layeranimation.min.') }}js"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script  src="{{ asset('js/index.js') }}"></script>
<script src='https://tympanus.net/Development/Slicebox/js/jquery.slicebox.js'></script>
<script  src="{{ asset('js/indexs.js') }}"></script>
<script>
      $(document).ready(function()
      {
         $("#mostrarmodal").modal("show");
      });
    </script>
<!-- <script>
	
	$(function(){
	$(".fab,.backdrop").click(function(){
		if($(".backdrop").is(":visible")){
			$(".backdrop").fadeOut(125);
			$(".fab.child")
				.stop()
				.animate({
					bottom	: $("#masterfab").css("bottom"),
					opacity	: 0
				},125,function(){
					$(this).hide();
				});
		}else{
			$(".backdrop").fadeIn(125);
			$(".fab.child").each(function(){
				$(this)
					.stop()
					.show()
					.animate({
						bottom	: (parseInt($("#masterfab").css("bottom")) + parseInt($("#masterfab").outerHeight()) + 70 * $(this).data("subitem") - $(".fab.child").outerHeight()) + "px",
						opacity	: 1
					},125);
			});
		}
	});
});
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.5.0/velocity.min.js"></script>

<script>
	$('#zoomBtn').click(function() {
  $('.zoom-btn-sm').toggleClass('scale-out');
  if (!$('.zoom-card').hasClass('scale-out')) {
    $('.zoom-card').toggleClass('scale-out');
  }
});

$('.zoom-btn-sm').click(function() {
  var btn = $(this);
  var card = $('.zoom-card');

  if ($('.zoom-card').hasClass('scale-out')) {
    $('.zoom-card').toggleClass('scale-out');
  }
  if (btn.hasClass('zoom-btn-person')) {
    card.css('background-color', '#f2f2f2');
  }
   // else if (btn.hasClass('zoom-btn-doc')) {
  //   card.css('background-color', 'blue');
  // } else if (btn.hasClass('zoom-btn-tangram')) {
  //   card.css('background-color', 'blue');
  // } else if (btn.hasClass('zoom-btn-report')) {
  //   card.css('background-color', 'blue');
  // } else {
  //   card.css('background-color', 'blue');
  // }
});

</script>