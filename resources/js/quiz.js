$(function() {
    // on load fetch 
    const index = $("#current_index").val();

    $.ajax({
        url: "/question/"+index,
        method: 'GET',
        success: function (res) {
            const question = decodeHtmlEntities(res.question);

            $("#question").text(question);

            $("#answers").html("");
            
            $.each(res.answers, function(k, v) {
                const answerStr = decodeHtmlEntities(v);
                const answer = '<div class="form-check">'+
                    '<input class="form-check-input" type="radio" name="answer" id="answer_'+k+'" value="'+v+'">'+
                    '<label class="form-check-label" for="answer_'+k+'">'+
                        answerStr+
                    '</label>'+
                '</div>';
                $("#answers").append(answer);
            });
        },
        error: function (res) {
            $("#question").text("There was an error! " + res.responseJSON.error);
        }
    });


    // when clicking next button, save the answer


    // small trick to avoid enconding issues
    function decodeHtmlEntities(str) {
        return $('<textarea/>').html(str).text();
    }
});