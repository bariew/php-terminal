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
            var $content1 = $('#terminal-input').html().replace('<br>', "\n").substring(
                0, ($terminalTextOffset - $('#terminal-search').val().length));
            var $content2 = $('#terminal-input').html().replace('<br>', "\n").substring($terminalTextOffset);
            var $text = ($content1 + ui.item.value + $content2).replace(/\n/, "<br>");
            console.log($text);
            $('#terminal-input').html($text).trigger('onkeyup');
            $('#terminal-input').focus().setCursorPosition($terminalTextOffset);
        }
    });
    $('#terminal-input').keyup(function(event){
        $terminalTextOffset = getCaretCharacterOffsetWithin(this);
        var $content = $(this).text().substring(0, $terminalTextOffset).split(/[^\w]/);
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
function getCaretCharacterOffsetWithin(element) {
    var caretOffset = 0;
    var doc = element.ownerDocument || element.document;
    var win = window;
    var sel;
    if (typeof win.getSelection != "undefined") {
        sel = win.getSelection();
        if (sel.rangeCount > 0) {
            var range = win.getSelection().getRangeAt(0);
            var preCaretRange = range.cloneRange();
            preCaretRange.selectNodeContents(element);
            preCaretRange.setEnd(range.endContainer, range.endOffset);
            caretOffset = preCaretRange.toString().length;
        }
    } else if ( (sel = doc.selection) && sel.type != "Control") {
        var textRange = sel.createRange();
        var preCaretTextRange = doc.body.createTextRange();
        preCaretTextRange.moveToElementText(element);
        preCaretTextRange.setEndPoint("EndToEnd", textRange);
        caretOffset = preCaretTextRange.text.length;
    }
    return caretOffset;
}