<script
   src="https://code.jquery.com/jquery-3.6.4.js"
   integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
   crossorigin="anonymous"
   ></script>
<script
   src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
   integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
   crossorigin="anonymous"
   ></script>
<script type="text/javascript">
   $(".toggle").click(function() {
     // toggle Forms
     $("#signup").toggle();
     $('#login').toggle();
   })
   
   $('#diary').bind('input propertychange', function(){
     $.ajax({
       method: 'POST',
       url: 'updatedb.php',
       data: { content: $("#diary").val()}
     })
   });
</script>
</body>
</html>