    $(document).ready(function(){
        $("#submit").show('fast');
        $("#submit").click(function () {
            var thesezips=$('#form_zips').val();
            var dist=$('#form_miles').val();
             
            $.ajax({
                url: "getradiuszips.php",
                type: "POST",
                dataType:'json',
                
                data: {zipscsv: thesezips, miles: dist},
                //async: false,//keep loading gif from showing up
                success: function (data) {
                    console.log(data);
                    $('#form_zips').val(data);
                },
                error: function () {
                    alert( "Posting failed." );
                    $('#message').html("");
                },
            });
        
        return false;
        });
    });

 


