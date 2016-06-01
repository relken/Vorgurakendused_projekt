// kood võetud: http://www.stepblogging.com/username-availability-check-using-php-mysqli-and-jquery/

$(document).ready(function(){
    $('#username').keyup(function(){
        var username = $('#username').val();
        if(username.length > 2) {
            $('#username_availability_result').html('Loading..');
            var post_string = 'username='+username;
            $.ajax({
                type : 'POST',
                data : post_string,
                url  : 'usernamecheck.php',
                success: function(responseText){
                if(responseText == 0){
                    $('#username_availability_result').html('<span class="success" style="color:green">Sobib! Sellist kasutajanime veel ei ole</span>');
                }else if(responseText > 0){
                    $('#username_availability_result').html('<span class="error" style="color:red">Selline kasutajanimi juba eksisteerib, palun vali uus!</span>');
                }
            }
        });
    }else{
        $('#username_availability_result').html('');
    }
    });
});