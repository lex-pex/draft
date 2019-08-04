$(document).ready(function(){

$('#title').blur(function() {
    validateTitle();
});

$('#title').keyup(function() {
    validateTitle();
});

$('#text').blur(function() {
    validateText();
});

$('#text').keyup(function() {
    validateText();
});

var validationNeeded = true;

$("form").submit(function(event) {
    if (validationNeeded) { 
      event.preventDefault();
    }
});

function validateTitle() {
    var title = $('#title');
    var titleText = title.val();
    var titleMessage = '<small class="text-danger" id="titleMessage"> TITLE has to be more than 3 character and less 50</small>';

    if(length(3, 50, titleText)) {
        title.addClass('is-invalid');
        if(!$( "#titleMessage").length)
            title.after(titleMessage);
        validationNeeded = true;
    } else {
        title.removeClass('is-invalid');
        $( "#titleMessage").remove();
        validationNeeded = false;
    }
}

function validateText() {
    var text = $('#text');
    var textText = text.val();
    var textMessage = '<small class="text-danger" id="textMessage"> TEXT has to be more than 50 character and less 250</small>';

    if(length(50, 250, textText)) {
        text.addClass('is-invalid');
        if(!$( "#textMessage").length)
            text.after(textMessage);
        validationNeeded = true;
    } else {
        text.removeClass('is-invalid');
        $( "#textMessage").remove();
        validationNeeded = false;
    }
}

function length(min, max, str) {
    return str.length < min || str.length > max;
}

});



