$(document).on('click', '.js-like', function() {
    console.log('liked');
    
    let postId = $(this).siblings('.post-id').val();
    like(postId, $(this));
});


function like(postId, clickedBtn) {

    $.ajax({
        url: 'post/' + postId + '/like',
        type: 'POST',
        dataType: 'json',
       
        headers: {
            'X-CSRF-TOKEN': 
            $('meta[name="csrf-token"]').attr('content')
        }
    }).done((data) => {
        console.log(data);
       
        let num = clickedBtn.siblings('.js-like-num').text();


        num = Number(num);


        clickedBtn.siblings('.js-like-num').text(num + 1);

 
        changeLikeBtn(clickedBtn);
    }).fail((error) => {
        console.log(error);
    });
}



$(document).on('click', '.js-dislike', function() {
    console.log('dislike');
    let postId = $(this).siblings('.post-id').val();

    dislike(postId, $(this));

});

function dislike(postId, clickedBtn) {

    $.ajax({
        url: 'post/' + postId + '/dislike',
        type: 'POST',
        dataType: 'json',
        
        headers: {
            'X-CSRF-TOKEN': 
            $('meta[name="csrf-token"]').attr('content')
        }
    }).done((data) => {
        console.log(data);
 
        let num = clickedBtn.siblings('.js-like-num').text();

        num = Number(num);


        clickedBtn.siblings('.js-like-num').text(num - 1);

        changeLikeBtn(clickedBtn);
    }).fail((error) => {
        console.log(error);
    });
}


function changeLikeBtn(btn) {
    btn.toggleClass('fa-thumbs-o-up').toggleClass('fa-thumbs-up');
    btn.toggleClass('js-like').toggleClass('js-dislike');
}

