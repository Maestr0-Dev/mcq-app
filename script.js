function checkAns(user_ans, real_ans, num) {
    if (user_ans == real_ans) {
        console.log('Correct');
        $.ajax({
            type: 'POST',
            url: 'check_answer.php',
            data: { user_ans: user_ans, real_ans: real_ans, num: num },
            success: function(data) {
                console.log(data);
            }
        });
    } else {
        console.log('Incorrect');
    }
}