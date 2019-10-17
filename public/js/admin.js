/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function setCookie(name, value, days) {
    var expires = '';

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }

    document.cookie = name + "=" + value + expires + "; path=/";
}

function slugify(text) {
    text = rus_to_latin(text);
    return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
}

function codefy(text) {
    text = rus_to_latin(text);
    return text.toString().toLowerCase()
            .replace(/\s+/g, '_')           // Replace spaces with _
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '_')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
}

function rus_to_latin(str) {

    var ru = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
        'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i',
        'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
        'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh',
        'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya'
    }, n_str = [];

    str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');

    for (var i = 0; i < str.length; ++i) {
        n_str.push(
                ru[ str[i] ]
                || ru[ str[i].toLowerCase() ] == undefined && str[i]
                || ru[ str[i].toLowerCase() ].replace(/^(.)/, function (match) {
            return match.toUpperCase()
        })
                );
    }

    return n_str.join('');
}

function checkDec(el, scale=2) {
    if(scale == 1){
        var RE = /^\d*\.?\d{0,1}$/;
    }else{
        var RE = /^\d*\.?\d{0,2}$/;
    }

    if (!RE.test(el))
        el = el.substring(0, el.length - 1);

    return el;
}