// https://stackoverflow.com/questions/42875726/speechsynthesis-speak-in-web-speech-api-always-stops-after-a-few-seconds-in-go
let p = document.querySelectorAll('p, li, h2, h3, pre');

// var socket = io('https://aucklandcomputerscience-test.herokuapp.com/');
var socket = io('http://localhost:5000/');


let ttsOn = false;
let auto = true;
let lastEl = null;

let mode = {
    selectedMode:'reading',
    reading: function(){
        this.selectedMode = 'reading'
    },
    presentation: function(){
        this.selectedMode = 'presentation'
    }
}
var synth = window.speechSynthesis;
let chromeHack = {
    debugMode:false,
    log:function(obj){
        if(this.debugMode)
            console.log(obj);
    },
    loop:null,
    synth:null,
    setSynth:function(synth){
        this.synth = synth;
    },
    reading:function(){
        this.log("CHROME HACK START");
        let that = this;
        this.loop = setInterval(function () {
            that.log("CHROME HACK WORKING (each 14 seconds)");
            that.log(["this.synth.speaking",this.synth.speaking]);
            if (!this.synth.speaking){
                clearInterval(this.loop);
            } else {
                this.synth.resume();
            }	
        }, 14000);
    },
    stop:function(){
        this.log("CHROME HACK STOP");
        clearInterval(this.loop);
    }
}
chromeHack.setSynth(synth);

let amILogged = false;
let login = () => {
    console.log("LOGIN");
    amILogged = true;
    socket.emit('add user', 'm');
}



let read = (el) => {
    console.log("READING START");
    var utterThis = new SpeechSynthesisUtterance(el.textContent);
    // utterThis.rate = 1.2;
    
    synth.speak(utterThis);
    
        
    chromeHack.reading();//HACK to read (because of google) more than 15 seconds.
    ttsOn = true;
    // if(mode.selectedMode !== 'reading'){
        focusOn2(el);
    // }
    start(el);
    utterThis.onend = function(event) {
        console.log("autofinish"); 
        if(mode.selectedMode == 'reading'){

            setTimeout(function () {
                if(ttsOn) finish(event);
            },3000);   
        } else {
            console.log("Jumping setTimeout");
            if(ttsOn) finish(event);
        }
    }
}

let finish = (event, el) =>{
    console.log('Finishing');
    
    synth.cancel();
    chromeHack.stop();//HACK to read (because of google) more than 15 seconds.
    highlightBack(lastEl);
    ttsOn = false;
    console.log('Finished');
    if(auto && lastEl.next !== null){

        switch (mode.selectedMode) {
            case 'reading':
                console.log('calling next one');
                // read(lastEl.next)
                processElement(lastEl.next)
                break;
        
            default:
                break;
        }        
    }
}

let processElement = (el) =>{
    if(mode.selectedMode == 'reading'){
        socket.emit('new message', "focusOnElement-"+el.remoteId);
    }
    if(el.tagName.toLowerCase() == 'pre'){
        clearInterval(codeScroll);
        showCode(el);
    } else {
        read(el);
    }
    
}
let start = (el) =>{
    lastEl = el;
    highlight(el);
}


let smoothMode = false;
let focusOn = (el) => {
    console.log("Focusing")
    el.scrollIntoView(true);
    if(smoothMode){
        console.log('smoothMode',smoothMode);
        let html = document.querySelector('html');
        html.style.scrollBehavior = 'smooth';
        setTimeout(function(){
            window.scrollBy(0, -70);
        },500)
    } else {
        console.log('smoothMode',smoothMode);
        window.scrollBy(0, -70);
    }
    
}
let focusOn2 = (el) => {
    console.log("focusing")
    switch (mode.selectedMode) {
        case 'reading':
            showInmodal(el)
            break;
    
        default:
            break;
    }
    el.scrollIntoView(true);
    if(smoothMode){
        console.log('smoothMode',smoothMode);
        let html = document.querySelector('html');
        html.style.scrollBehavior = 'smooth';
        setTimeout(function(){
            window.scrollBy(0, -70);
        },500)
    } else {
        console.log('smoothMode',smoothMode);
        window.scrollBy(0, -70);
    }
    
}
let highlight = (el) => {
    el.style.textShadow = '0 0 3px #FF0000';
    el.transition = '0.5s all ease';
};
let highlightBack = (el) => {
    if( el !== null)
        el.style.textShadow = '0 0';
}

let codeScroll = null;
let showCode = (el) => {
    console.log(el.tagName)
    modal.style.display = "block";
    modalDiv.innerHTML = "";
    modalDiv.style.fontSize = '1em';
    modalDiv.appendChild(el.cloneNode(true))
    modalDiv.classList.toggle("full-code")
    modalDiv.classList.add("modal-content-full-code")
    let tmp = []
    codeScroll = setInterval(function () {
        modal.scrollBy(0, 2);
        tmp.push($(modal).scrollTop());
        if(tmp.length==2 &&tmp[0]==tmp[1]){
            clearInterval(codeScroll);
        }
        
    },100)
};


let showInmodal = function(el){
    
    //related with showCode() function
    modalDiv.classList.remove("full-code")
    modalDiv.classList.remove("modal-content-full-code")
    modal.style.display = "block";
    modalDiv.innerHTML = "";
    let out
    if(el instanceof Object){
        out = el.textContent;   
        
    }
    if(el instanceof String){
        out = el;
    }
    if(out.length > 390){
        modalDiv.style.fontSize = '1.25em';
        modalDiv.style.color = 'purple';
    } else if (out.length > 300) {
        modalDiv.style.fontSize = '1.5em';
        modalDiv.style.color = 'red';
        console.log('red');
    } else if (out.length > 200) {
        modalDiv.style.fontSize = '1.75em';
        modalDiv.style.color = 'green';
    } else {
        modalDiv.style.fontSize = '2em';
        modalDiv.style.color = 'blue';
        console.log('blue');
    }
    
    modalDiv.innerHTML = el.textContent;
}

for (i = 0; i < p.length; i++) {
    p[i].next = (p[i+1])?p[i+1]:null;
    p[i].remoteId = i;
    p[i].onclick = function(){
        
        
        mode.reading();
        console.log('mode', mode.selectedMode);
        if(!ttsOn){
            auto = true;
            // read(this)    
            processElement(this)
        } else {
            console.log("stoppin by click");
            console.log("auto", auto);;  
            finish()
        }
        
    };
}

let listening = () => {
    console.log("LISTENING");
    socket.on('new message', function (data) {
        console.log(data);
        if(ttsOn) return;
       // mode.presentation();
        remote.dispacher(data);
        // if(data.username == 'm' ){
        //     console.log('ruuuuuun it', mode.selectedMode);
        //     auto = false;
        //     finish();
        //     read(getElement());
            
        // }
    });
}
let getElement = () => {
    if(lastEl !== null){
        return lastEl.next;
    }
    return p[0];
}
let focusOnElement = (remoteId) => {
        mode.presentation();
        auto = false;
        finish();
        // read(p[remoteId]);
        processElement(p[remoteId])
}
let remoteFocus = () => {
    mode.presentation();
    auto = false;
    finish();
    // read(getElement());
    processElement(getElement());
}

let remote = {
    username:null,
    setUsername:function(username){
        this.username = username
    },
    dispacher:function(data){
        console.log('dispacher', data);
        if(!this.validateUser(data)) return;
        console.log('dispacher', data);
        let callable =  data.message.split('-');
        if(callable.length != 2){
            return;
        }
        if(typeof this.container[callable[0]] === 'function'){
            this.container[callable[0]](callable[1]);
        }
    },
    validateUser:function(data){
        return true;
        if(data.username === this.username){
            return true;
        }
        return false;
    },
    container:{
        'next-text':remoteFocus,
        'focusOnElement':focusOnElement
    }
}
remote.setUsername('m')
window.onbeforeunload = function() {
    console.log("closing window");
    auto = false;
    finish();
    return false;
};


 

