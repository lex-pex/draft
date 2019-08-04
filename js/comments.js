/**
 * Initializing Block 
 */
var base = $("[name=base]").attr('content');
var routs = {
    index: base + '/commentsIndex',
    store: base + '/commentStore',
    update: base + '/commentUpdate',
    edit: base + '/commentEdit',
    destroy: base + '/commentDestroy'
};
var cmt = $('#cmt');
var post_id = cmt.attr('data-id');
var validationNeeded = false;
var updatedId = 0;

/** 
 * Init Block Ends
 * Run the Comment Plugin with RestFull method Index
 */

if(post_id)
    indexComments();

/** 
 * Set the plugin in Standby Mode
 * Catch the "New Comment" event
 */
cmt.keyup(function(event) {
    if(validationNeeded)
        validationWarning();
    if((event.keyCode || event.which) == '13') {
        var txt = cmt.val().trim();
        if(checkLength(txt)) {
            store(txt);
        } else {
            validationWarning();
        }
    }
});

function store(commentText) {
    if(updatedId == 0) {
        $.post(routs.store,
            { post_id: post_id, text: commentText },
            function(data) { var d = data; });
    } else {
        $.post(routs.update,
            { "id" : updatedId, text: commentText  },
            function(data){ updatedId = 0; });
    }
    cmt.val('').blur();
    setTimeout(indexComments, 500);
}

function indexComments() {
    $.post(
        routs.index,
        { id : post_id },
        function(data) {
            var cmts = JSON.parse(data);
            var showCmt = $('#showCmt');
            showCmt.empty();
            for (var i = cmts.length - 1; i >= 0; i--)
                appendComment(showCmt, cmts[i]);
        }
    );
}

function appendComment(element, com) {
  var c = $('<div class="comment" data-comment-id="' + com.id + '"></div>').text(com.fields.text);
  element.append(c).append(dropMenu(com.id)).append('<br/>');
}

function dropMenu(id) {
    var m = $('<span class="comment-tools">' +
        '<a onclick="commentEdit(' + id + ')">Edit</a> |' +
        '<a onclick="commentDelete(' + id + ')">Delete</a></span>');
    return m;
}

function commentEdit(id) {
    $.post(routs.edit,
            { "id" : id },
            function(data) {
                var c = JSON.parse(data);
                cmt.val(c.fields.text);
                updatedId = c.id;
            }
    );
}

function commentDelete(id) {
    $.post(routs.destroy,
        { "id" : id },
        function(data) {
            indexComments();
        }
    );
}

//** Validation Alerts
function validationWarning() {
    var t = cmt.val().trim();
    var message = '<small class="text-danger" id="message"> Text has to be more than 3 character and less 200</small>';
    if(!checkLength(t)) {
        cmt.addClass('is-invalid');
        if(!$( "#message").length)
            cmt.after(message);
        validationNeeded = true;
    } else {
        cmt.removeClass('is-invalid');
        $("#message").remove();
    }
}

//** Validate Length 
function checkLength(text) {
    var l = text.length;
    return l >= 3 && l <= 200;
}




























