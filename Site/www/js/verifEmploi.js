var verifEmployee = new RegExp('id=');
    var verifNavEmploi = new RegExp('navEmployee=');
    var verifType = new RegExp('type=');
    if (!verifEmployee.test(document.cookie) || !verifNavEmploi.test(document.cookie) || !verifType.test(document.cookie)) {
        deco();
    }