

jQuery(function($) {
  $( ".rushfilter-row" ).accordion({
    collapsible: true,
    animate: 100,
    });

 



    // Post load more
    $(document).ready(function(){
      // Range value showing filter page
      var deafultGetPostCount = $('.value-hidden').text();
      $('#rushfilter-rangevalue').val(deafultGetPostCount);
      $('#rushfilter-range').on('change', function(){
        const $this = $(this);
        // set value on label
        $('#rushfilter-rangevalue').val($this.val());
        $('.value-hidden').text($this.val());
      });

      var showingPost =  $('.value-hidden').text();


      $("#rushfilter-main .rushfilter-post-item").slice(0, showingPost).show();
      $(".rushfilter-loadmore").on("click", function(e){
        e.preventDefault();
        $("#rushfilter-main .rushfilter-post-item:hidden").slice(0, showingPost).slideDown();
        var currentTotalPostCount = $("#filterResponse .rushfilter-post-item").length;
				$('.rushfilter-loadmore-row .rushfilter-post-count .total').text(currentTotalPostCount);
        var showedItem = $("#filterResponse .rushfilter-post-item:visible").length;
        $('.rushfilter-loadmore-row .rushfilter-post-count .current').text(showedItem);
      });
      
    });



    // Total post count
    $(document).ready(function(){
				var currentTotalPostCount = $("#filterResponse .rushfilter-post-item").length;
				$('.rushfilter-loadmore-row .rushfilter-post-count .total').text(currentTotalPostCount);
        var showedItem = $("#filterResponse .rushfilter-post-item:visible").length;
        $('.rushfilter-loadmore-row .rushfilter-post-count .current').text(showedItem);
			})


    // Global post type ajax request jquery
    $(document).ready(function(){
      $('#rushpostfilter input').click(function() {
        var formID = $('#rushpostfilter').serialize();
        $.ajax({
          url:rushfilter_frontend_global_url.ajax_url,
          data:formID,
          type:'post',
          beforeSend: function() {
            $('#preloader').removeClass('hidden');
          },
          success: function( data ) {
            console.log(data);
          $('#filterResponse').html(data);


            // Post load more
            var showingPost =  $('.value-hidden').text();
            $("#rushfilter-main .rushfilter-post-item").slice(0, showingPost).show();
            $(".rushfilter-loadmore").on("click", function(e){
              e.preventDefault();
              $("#rushfilter-main .rushfilter-post-item:hidden").slice(0, showingPost).slideDown();
              var currentTotalPostCount = $("#filterResponse .rushfilter-post-item").length;
              $('.rushfilter-loadmore-row .rushfilter-post-count .total').text(currentTotalPostCount);
              var showedItem = $("#filterResponse .rushfilter-post-item:visible").length;
              $('.rushfilter-loadmore-row .rushfilter-post-count .current').text(showedItem);
            });



            var currentTotalPostCount = $("#filterResponse .rushfilter-post-item").length;
            $('.rushfilter-loadmore-row .rushfilter-post-count .total').text(currentTotalPostCount);
            var showedItem = $("#filterResponse .rushfilter-post-item:visible").length;
            $('.rushfilter-loadmore-row .rushfilter-post-count .current').text(showedItem);



          },
          complete: function(){
            $('#preloader').addClass('hidden');
          },
        })
      });
    }); 
    
});

