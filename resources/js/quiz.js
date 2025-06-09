$(function() {
    let index = 0;
    loadQuestion(index);

    $("#current_index").text((index+1));

    $("#next-button").on("click", function(e) {
        e.preventDefault();

        const csrf = $('input[name="_token"]').val();
        
        // we disable button to not trigger index increment again
        // before question fully loads
        $("#next-button").prop("disabled", true);

        $.ajax({
            url: "/answer",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            data: {
                answer: $("input[name='answer']:checked").val(),
                index: index,
                question: $("#question").text()
            },
            success: function(res) {
                index++;
                loadQuestion(index);
            },
            error: function(err) {
                $("#question").text("There was an error saving the answer!");
                $("#answers").html("");
                $("#next-button").prop("disabled", true);
            }
        });
    });

    function loadQuestion(index) {
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

                $("#next-button").prop("disabled", false);

            },
            error: function (res) {
                $("#question").text("There was an error! " + res.responseJSON.error);
                $("#next-button").prop("disabled", true);
            }
        });
    }

    // small trick to avoid enconding issues
    function decodeHtmlEntities(str) {
        return $('<textarea/>').html(str).text();
    }
});