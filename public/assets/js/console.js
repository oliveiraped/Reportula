jQuery(document).ready(function($) {
        //$('#console').terminal("json-rpc-service-demo.php", {
        $('#console').terminal("command", {
            login: false,
            outputLimit : 0,
            prompt: '$>',
            width: '100%',
            height: 600,
            greetings: "Reportula Bacula Console",
            onBlur: function() {
                // the height of the body is only 2 lines initialy
                return false;
            }
        });
    });


