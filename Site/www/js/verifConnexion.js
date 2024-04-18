var test= new RegExp('id');
if(document.cookie=="" || !test.test(document.cookie)){
    window.location="index.php";
}