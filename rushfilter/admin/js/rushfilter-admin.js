jQuery(function($) {
$(".rushfilter-admin-wrap .nav-menu li").click(function(e) {
	e.preventDefault();
	$(".rushfilter-admin-wrap .nav-menu li").removeClass("active");
	$(this).addClass("active");
});


$(".closeinfo").click(function(){
	$(".modal").removeClass("open");
});
$("#getinfo").click(function(){
	$(".modal").addClass("open");
});


$("#rushfilterCreate").click(function(){
	$("#rushfilterCreateForm").slideToggle();	
});


$(".closeeditmodal").click(function(){
	$(".rushfilter-edit-modal").removeClass("open");
});
$(".openeditmodal").click(function(){
	$(".rushfilter-edit-modal").addClass("open");
});



});







	
