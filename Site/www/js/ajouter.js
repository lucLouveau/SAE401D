function ajout(div, store){
    var type=div.parentElement.id;
    var erreur="";
    var params=div.parentElement.children[0].children;
    var cookies=document.cookie.split("; ");
    for(var i=0;i<cookies.length;i++){
        var apiTest=new RegExp('apiKey=');
        if(apiTest.test(cookies[i])){
            var apiKey=cookies[i].split("=")[1];
        }
    }
    var valeur=new Object();
    for(var i=0;i<params.length;i++){
        if(params[i].tagName=="SELECT"){
            if(params[i].id=='storeSel' || params[i].id=='storeStockSel'){
                store=params[i].value;
            }
            valeur[params[i].name]=params[i].value;
        }
        else if(params[i].tagName=="INPUT"){
            if(params[i].value==""){
                erreur+=params[i].name+", ";
            }
            else if(params[i].type=="email"){
                var regex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
                if(regex.test(params[i].value)){
                    valeur[params[i].name]=params[i].value;
                }
                else{
                    erreur+=params[i].name+", ";
                }
            }
            else if(params[i].type=="password"){
                if(params[i].value.length>=8){
                    var lettreMin= /[a-z]+/;
                    var lettreMaj= /[A-Z]+/;
                    var chiffre=/[0-9]+/;
                    var caractère=/[#\.\?!@\$%\^&\*-_]/;
                    if(lettreMaj.test(params[i].value)&&lettreMin.test(params[i].value)&&chiffre.test(params[i].value)&&caractère.test(params[i].value)){
                        valeur[params[i].name]=params[i].value;
                    }
                    else{
                        erreur+=params[i].name+", ";
                    }
                }
                else{
                    erreur+=params[i].name+", ";
                }
            }
            else{
                valeur[params[i].name]=params[i].value;
            }
        }
        
        
    }
    if(type!="store" || type!="brand" || type!="category"){
        valeur['store']=store;
    }
    if(erreur==""){
       $.ajax("https://dev-louveau222.users.info.unicaen.fr/bikestores/"+type+"s?apiKey="+apiKey, {
            "type" : "POST",
            "data" : valeur,
            "dataType" : "html",
            "success": function(){
                message(type+" has been created", 'green');
            }
        })
    }
    
}
