$(document).ready(function () {

        $('#adress').autocomplete({
            source: function (request, response) {
                $.ajax({
                    dataType: "json",
                    type : 'Get',
                    url: 'auto',
                    data:
                    {
                        address: request.term
                    },
                    success: function(data) {
                        response($.map( data, function( item ) {
                            return {
                                label: item.street,
                                value: item.street
                            }
                        }));
                    }
                });
            },
            minLength: 3
        });

    $('#save').on('click', function () {
        var button = $(this);
        var id = button.val();
        $.ajax({
            type: "POST",
            url: "/edit.html",
            data: {
                id: id,
                street: button.closest('.edit_modal').find('.street').val(),
                kids: button.closest('.edit_modal').find('.kids').val()
            },
            success: function(result) {
                button.closest('.edit_modal').modal('hide');
                $('#alerts').append(
                    '<div class="alert alert-success alert-dismissable">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        'Sėkmingai atnaujinta!' +
                    '</div>'
                );
            },
            error: function(result) {
                alert('error');
            }
        });
    })
});