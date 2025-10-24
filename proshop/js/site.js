$(document).bind('cbox_closed', function(){
    //AJAX CALL TO RELOAD HEADER
    $("#bag").load('includes/bag.php');
    
});

$(document).ready(function() {	
	
	$("#bag").load('includes/bag.php');
	
	// Fade on Hover - Site Wide
	$('.subMenuLink').each(function(index) {
		$(this).hover(function () {
			$(this).fadeTo(100, .5);
		}, function () {
			$(this).fadeTo(400, 1);
		});
	});	
	
	
	
	// Menus - Site Wide
	$('.menuItem').hoverIntent(makeTall,makeShort);

	function makeTall(){ $(this).find('ul').toggle(); }
	function makeShort(){ $(this).find('ul').toggle(); }			
	
	
	// MENU FUNCTIONS
	//$('#menu li h4 a').hoverIntent(function () {

	//	var thisID = $(this).attr("id");

	//	$(".section").slideUp("slow");
	//	var checkElement = $(".section_"+thisID);

	//	if ($(".section_"+thisID).is(':visible')) {
			//alert('visible');
	//	} else {
	//		checkElement.slideDown("slow");
	//	}

	//}, function () {

	//});
	
	// Every 4th Product Remove Right Margin
	$('.item').filter(function(index){
		
		return (index%4 == 3);
		
	}).css('margin-right','0px');
	
	
	//Colorbox Call 
	$(".itemPopup").colorbox({top:"16%", width:"850px", height:"563px", iframe:true});

	
	//jqZoom
	$('.jqzoom').jqzoom({
		zoomType: 'standard',
		lens:true,
		preloadImages: false,
		alwaysOn:false,
		zoomWidth: 200,
		zoomHeight: 200,
		xOffset:90,
		yOffset:30,
		position:'left'
	});
	//$('.jqzoom').jqzoom();

	
	
	// Other..
	$('#content-home #featured img:last').css('margin-right','0px');
	
	//Temp fix for the empty menu list item issue with EOW nav...
	$('.menuCat').filter(function() {
        return $.trim($(this).text()) === ''
    }).remove()
	
	// In The Media - gray bg - odd
	$('.itemRow:even').css('background-color','#F5F5F5');
	
	
	
	
	
});// end Doc ready