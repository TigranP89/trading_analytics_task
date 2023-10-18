$(function (){
  $(document).ready(function() {
    $('#load-data').click( function (){
      $("#load-data").prop("disabled", true);
      $(".total").html(0);
      $(".added").html(0);
      $(".updated").html(0);

      $.ajax({
        url: "/import",
        type: "GET",
        success:function (res) {
          console.log(res);

          $("#load-data").prop("disabled", false);
          $(".total").html(res.total);
          $(".added").html(res.added);
          $(".updated").html(res.updated);
        }
      });
    });
  });
})