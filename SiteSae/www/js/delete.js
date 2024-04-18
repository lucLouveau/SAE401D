async function delet(type, id, apiKey){
    await $.ajax("https://dev-louveau222.users.info.unicaen.fr/bikestores/"+type+"/" + id + "?apiKey=" + apiKey, {
        "type": "DELETE",
        "dataType": "json",
        "success": function() {
            message(type+" n°"+id+" has been deleted", 'green');
            $('tr>td.'+id)[0].parentElement.remove();
        }
    })
    
}