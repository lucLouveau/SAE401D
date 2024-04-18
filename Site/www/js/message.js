function message(message, color){
    var mes= document.createElement('div');
    mes.innerHTML="<p>"+message+"</p>";
    mes.classList.add('message');
    mes.style="color:"+color;
    $('body')[0].appendChild(mes);
    setTimeout(function(){
        mes.style="color:"+color+";bottom:10px;";
        setTimeout(function(){
            mes.style="color:"+color+";bottom:-34px;";
        },2000);
    },1000);
}