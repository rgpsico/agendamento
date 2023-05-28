

   

 function deleteUser(button, id, token) {
    $.ajax({
        url: '/api/users/' + id,
        type: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + token,
        },
        success: function(result) {
            $('.modal').modal('hide');
            $(button).closest(".linha_aluno-"+id).fadeOut();
        },
        error: function(request,msg,error) {
            console.log(error);
        }
    });
}

