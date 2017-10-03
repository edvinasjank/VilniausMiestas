/**
 * Created by edvinas on 2017-09-28.
 */
$(document).ready(function () {

    $('#delete').click(function () {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.name;
        }).get();
        console.log(checkedValues);
    })


        $('#inputfilter').keydown(function(){
            filter = new RegExp($(this).val(),'i');
            $("#filterme tbody tr").filter(function(){
                $(this).each(function(){
                    found = false;
                    $(this).children().each(function(){
                        content = $(this).html();
                        if(content.match(filter))
                        {
                            found = true
                        }
                    });
                    if(!found)
                    {
                        $(this).hide();
                    }
                    else
                    {
                        $(this).show();
                    }
                });
            });
        });


});
