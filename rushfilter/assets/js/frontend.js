

jQuery(function($) {
  $( "" ).accordion({
    collapsible: true,
    });

    // Range value showing filter page
      $('#rushfilter-range').on('change', function(){
        const $this = $(this);
        // set value on label
        $('#rushfilter-rangevalue').val($this.val());
      });


    // Post load more
    $(document).ready(function(){
      $("#rushfilter-main .rushfilter-post-item").slice(0, 6).show();
      $(".rushfilter-loadmore").on("click", function(e){
        e.preventDefault();
        $("#rushfilter-main .rushfilter-post-item:hidden").slice(0, 6).slideDown();
      });
      
    });


    // Global post type ajax request jquery
    $(document).ready(function(){
      $('#rushpostfilter').click(function() {
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
            $("#rushfilter-main .rushfilter-post-item").slice(0, 6).show();
            $(".rushfilter-loadmore").on("click", function(e){
              e.preventDefault();
              $("#rushfilter-main .rushfilter-post-item:hidden").slice(0, 6).slideDown();
            });

          },
          complete: function(){
            $('#preloader').addClass('hidden');
          },
        })
      });
    }); 
    
});

