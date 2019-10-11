let button = document.querySelector('.chat-buttom');
let close = document.querySelector('.close-chat');
let toggle = true;
let togglef = () =>
{
    
    document.querySelector('.chat-container').classList.toggle('chat')
    document.querySelector('.chat-buttom').style.display = (toggle)?"none":"block";
    document.querySelector('iframe').style.display =  (toggle)?"block":"none";    
    document.querySelector('.close-chat').style.display =  (toggle)?"block":"none";    
    toggle = !toggle;
}
button.onclick = close.onclick = togglef;