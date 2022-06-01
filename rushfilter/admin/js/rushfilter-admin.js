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



// Ajax request for post type tax name filter create form
$(document).ready(function(){
	$('#select-posttype').on('change', function(event) {
		var selectedPostType = $(this).find(":selected").val();
		event.preventDefault();
		$.ajax({
		  type : 'post',
		  url : url_ajax_global.ajax_url,
		  data : {
			action: 'get_post_tax_name_action',
			itemId: selectedPostType,
		  },
		  success: function( data ) {
			$('#rushfilter-post-type-tax').html(data);
			$('#rushfilter-edit-post-type-tax').html(data);
		  }
		})
	});
}); 



  


});









	
