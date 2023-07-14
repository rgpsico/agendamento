$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

 
 function deleteUser(routeApi, button, id, token) {
    $.ajax({
        url:routeApi + id,
        type: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + token,
        },
        data:{
            "_token": "{{ csrf_token() }}"
        },
        success: function(result) {
            console.log(result)
            window.location.reload
            $('.modal').modal('hide');
            $(button).closest(".linha_-"+id).fadeOut();
            var modalElement = $('.modal');
            if (modalElement.length) {
                modalElement.modal('hide');
            }
            $(button).closest(".linha_-"+id).fadeOut();
                },
        error: function(request,msg,error) {
            console.log(error);
        }
    });
}

