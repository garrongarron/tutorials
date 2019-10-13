
let p = document.querySelectorAll('p, li');

let ttsOn = false;
var synth = window.speechSynthesis;
let read = (el) => {
    
    var utterThis = new SpeechSynthesisUtterance(el.textContent);
    // utterThis.rate = 1.2;
    
    window.speechSynthesis.speak(utterThis);
    ttsOn = true;
    start(el);
    utterThis.onend = function(event) {
        finish(event, el);
    }
}

let finish = (event, el) =>{
    // el.style.background = 'none';
    // el.style.color = 'none';
    el.style.textShadow = '0 0';
    ttsOn = false;
}
let start = (el) =>{
    // el.style.background = 'blue';
    // el.style.color = 'red';
    el.style.textShadow = '0 0 3px #FF0000';
    el.transition = '0.5s all ease';
}


for (i = 0; i < p.length; i++) {
    p[i].onclick = function(){
        if(!ttsOn){
            read(this)    
        } else {
            synth.cancel();
        }
        
    };
}

var speakListener = function(utterance, options, sendTtsEvent) {
    sendTtsEvent({'event_type': 'start', 'charIndex': 0})
  
    // (start speaking)
    document.querySelector('body').style.background = 'red';
  
    sendTtsEvent({'event_type': 'end', 'charIndex': utterance.length})
  };
  
  var stopListener = function() {
    document.querySelector('body').style.background = 'none';
  };
  
 

