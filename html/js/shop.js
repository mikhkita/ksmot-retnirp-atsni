$(document).ready(function(){	

	$( "#slider-range" ).slider({
			range: true,
			min: 1000,
			max: 50000,
			values: [ 1000, 36000 ],
			slide: function( event, ui ) {
			$( "#amount-l" ).text( ui.values[ 0 ] );
			$( "#amount-r" ).text( ui.values[ 1 ] );
			}
		});
		$( "#amount-l" ).text( $( "#slider-range" ).slider( "values", 0 ) );
		$( "#amount-r" ).text( $( "#slider-range" ).slider( "values", 1 ) );
		
	});

	$(".fancy-img").fancybox({
        padding : 0
    });
    $(".images ul li").click(function(){
        $("#bg-img").css("background-image",$(this).css("background-image"));
        $("#bg-img a").attr("href",$(this).find("a").attr("href"));
        return false;
    });

});