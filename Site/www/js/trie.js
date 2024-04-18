async function trie(colomn, ordre){
    var trs=document.querySelectorAll('tbody>tr');
    for(var i=0;i<trs.length;i++){
        if(ordre==1 && trs[i-1]!=undefined){
            while(i>0 && trs[i].children[colomn].innerText.localeCompare(trs[i-1].children[colomn].innerText)==-1 ){
                await trs[i-1].before(trs[i]);
                i--;
                trs=document.querySelectorAll('tbody>tr');
            }
        }
        else if(i>0 && ordre==0){
            while(i>0 && trs[i].children[colomn].innerText.localeCompare(trs[i-1].children[colomn].innerText)==1){
                await trs[i-1].before(trs[i]);
                trs=document.querySelectorAll('tbody>tr');
                i--;
            }
        }
    }
}