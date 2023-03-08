(function ($) {

  $(function () {
     $('#datepicker').datepicker({
        dateFormat: "yy-mm-dd",
       onSelect: function (date){
            
         var url = $(location).attr('pathname');
         
         if(url.endsWith('/')){
            url = url.substring(0, url.length - 1);
         }
     
       window.open(url+"/report/"+ date , "_self");
       }    
     });
  });
})(jQuery);