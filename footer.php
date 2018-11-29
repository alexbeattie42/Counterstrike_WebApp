<?php echo '
 <footer>
    
    
<!-- <p>Hosted on GitHub Pages &mdash; Theme by <a href="https://github.com/orderedlist">orderedlist</a></p>-->
</footer>
<!--[if !IE]><script>fixScale(document);</script><![endif]-->
<script>
   var acc = document.getElementsByClassName("clickablerow");
   
   var i;
   
   for (i = 0; i < acc.length; i++) {
     acc[i].addEventListener("click", function() {
       var win = window.open(this.getAttribute("href"), "_blank");
       //window.location = this.getAttribute("href");
     });
   }
</script>
<script>
   var acc = document.getElementsByClassName("accordion");
   var i;
   
   for (i = 0; i < acc.length; i++) {
   acc[i].addEventListener("click", function() {
       this.classList.toggle("active");
       var panel = this.nextElementSibling;
       if (panel.style.maxHeight){
         panel.style.maxHeight = null;
       } else {
         panel.style.maxHeight = panel.scrollHeight + "px";
       } 
   });
   }
</script>

'


?>