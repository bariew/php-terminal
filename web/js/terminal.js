$terminalTextOffset = 0;
$('document').ready(function(){
    $('#terminal-input').focus();
    $('#terminal-search').autocomplete({
        minLength: 3,
        source: function (request, response) {
            jQuery.get("/site/search", {
                term: request.term
            }, function (data) {
                response(data);
            });
        },
        select : function (event, ui) {
            var $content1 = $('#terminal-input').val().substring(
                0, ($terminalTextOffset - $('#terminal-search').val().length));
            var $content2 = $('#terminal-input').val().substring($terminalTextOffset);
            $('#terminal-input').val($content1 + ui.item.value + $content2);
            $('#terminal-input').focus().setCursorPosition($terminalTextOffset + (
                ui.item.value.length - $('#terminal-search').val().length
            ));
            $.get("/site/doc", {term : ui.item.value}, function(data) {
                $('#function-doc').html(data);
            });
        }
    });
    $('#terminal-input').keyup(function(event){
        //if (event.keyCode == 40 && $('.ui-autocomplete li').length) {
        //    $('#terminal-search').focus().trigger(
        //        jQuery.Event( 'keydown', { keyCode: 40, which: 40 } )
        //    );
        //    return;
        //}
        $terminalTextOffset = getCaretCharacterOffsetWithin(this);
        var $content = $(this).val().substring(0, $terminalTextOffset).split(/[^\w:\\]/);
        var $word = $content.pop();
        $('#terminal-search').val($word).autocomplete('search', $word);
    })
});
$.fn.setCursorPosition = function(pos) {
    this.each(function(index, elem) {
        if (elem.setSelectionRange) {
            elem.setSelectionRange(pos, pos);
        } else if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    });
    return this;
};
function getCaretCharacterOffsetWithin(el) {
    if (el.selectionStart) {
        return el.selectionStart;
    } else if (document.selection) {
        el.focus();
        var r = document.selection.createRange();
        if (r == null) {
            return 0;
        }
        var re = el.createTextRange(),
            rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);
        return rc.text.length;
    }
    return 0;
}