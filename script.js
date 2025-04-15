
let userAnswers = {}; 

function checkAns(user_ans, real_ans, num) {
    // Get the previous answer for this question 
    if(userAnswers[num]!==""){
    let previousAnswer = userAnswers[num];
    
    // Update the user's answer
    userAnswers[num] = user_ans;

    if (previousAnswer === real_ans && user_ans !== real_ans) {
        // incrr to crr
        console.log('Score decremented for question ' + num);
        // sendAjaxRequest(user_ans, real_ans, num);
        $.ajax({
            type: 'POST',
            url: 'incrr_to_crr.php',
            data: { user_ans: user_ans, real_ans: real_ans, num: num ,  previousAnswer:previousAnswer},
            success: function(data) {
                console.log(data);
            }
        });
    } else if (previousAnswer !== real_ans && user_ans === real_ans) {
        // crr to incrr
        console.log('Score incremented for question ' + num);
        // sendAjaxRequest(user_ans, real_ans, num);  
        $.ajax({
            type: 'POST',
            url: 'crr_to_incrr.php',
            data: { user_ans: user_ans, real_ans: real_ans, num: num ,  previousAnswer:previousAnswer},
            success: function(data) {
                console.log(data);
            }
        });
    } else {
        console.log('No score change for question ' + num);
    }
}else{
    userAnswers[num] = user_ans;

    if (previousAnswer === real_ans && user_ans !== real_ans) {
        // incrr to crr
        console.log('Score decremented for question ' + num);
        // sendAjaxRequest(user_ans, real_ans, num);
        $.ajax({
            type: 'POST',
            url: 'incrr_to_crr.php',
            data: { user_ans: user_ans, real_ans: real_ans, num: num ,  previousAnswer:previousAnswer},
            success: function(data) {
                console.log(data);
            }
        });
    } else if (previousAnswer !== real_ans && user_ans === real_ans) {
        // crr to incrr
        console.log('Score incremented for question ' + num);
        // sendAjaxRequest(user_ans, real_ans, num);  
        $.ajax({
            type: 'POST',
            url: 'crr_to_incrr.php',
            data: { user_ans: user_ans, real_ans: real_ans, num: num ,  previousAnswer:previousAnswer},
            success: function(data) {
                console.log(data);
            }
        });
    } else {
        console.log('No score change for question ' + num);
    }
}

}

// function sendAjaxRequest(user_ans, real_ans, num) {
//     $.ajax({
//         type: 'POST',
//         url: 'check_answer.php',
//         data: { user_ans: user_ans, real_ans: real_ans, num: num ,  previousAnswer:previousAnswer},
//         success: function(data) {
//             console.log(data);
//         }
//     });
// }

 //function to join a community
 function join(com_id, id){
    $.ajax({
        type: 'POST',
        url: 'joinComm.php',
        data: { com_id: com_id, id: id },
        success: function(data) {
            console.log(data);
        }
    });
}