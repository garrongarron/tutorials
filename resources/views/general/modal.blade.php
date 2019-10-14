<link href="modal/css.css" rel="stylesheet" type="text/css"/>
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div class="modal-content" id="div01">asdasd</div>
    <div id="caption"></div>
</div>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
var modalDiv = document.getElementById("div01");

//delay the function because of jQuery is load at the end
window.onload = function(e){ 
    $('img').click(function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    })
}
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
  auto = false;
  finish();
  clearInterval(codeScroll); 
}
modal.addEventListener('scroll', function(){
    if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight){
        clearInterval(codeScroll);
        //modal.scrollTo(0,-$(modal).innerHeight()) 
    }
})

let closeModal = () => {
    modal.style.display = "none";
    modalDiv.innerHTML = "";
    auto = false;
}
</script>