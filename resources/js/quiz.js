$(function() {
    let index = 0;
    let isLoading = false;

    loadQuestion(index);

    $("#next-button").on("click", function(e) {
        e.preventDefault();

        const csrf = $('input[name="_token"]').val();
        
        if (isLoading) return;
        
        isLoading = true;

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
                if (res.redirect) {
                    window.location.href = "/results";
                    return;
                }

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
        $("#current_index").text((index+1));

        $.ajax({
            url: "/question/"+index,
            method: 'GET',
            success: function (res) {
                const question = decodeHtmlEntities(res.question);
                const category = decodeHtmlEntities(res.category);

                $("#question").text(question);
                $("#category").text(category);

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

                isLoading = false;
            },
            error: function (res) {
                $("#question").text("There was an error! " + res.responseJSON.error);
                isLoading = false;
            }
        });
    }

    // small trick to avoid enconding issues
    function decodeHtmlEntities(str) {
        return $('<textarea/>').html(str).text();
    }
});