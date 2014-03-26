
$(document).ready(function() {

    $('#testLDAP').click(function(){ 
    		$.ajax({
    			  url: "settings/testLdap",
    			  data: $('#settings').serialize(),
                  dataType: 'json',
                    success: function (data, text) {
                        bootbox.alert (data.html);
                    },
                    error: function (request, status, error) {
                        bootbox.alert ("<div class='alert alert-error'>Bind to Active Directory failed. Check the login credentials and or server details. AD said: Can't contact LDAP server</div>");
                    }
              });
    		return false; 
    });

    $('#syncLDAP').click(function(){ 
            $.ajax({
                  url: "settings/syncLdap",
                  data: $('#settings').serialize(),
                  dataType: 'json',
                    success: function (data, text) {
                        bootbox.alert (data.html);
                    },
                    error: function (request, status, error) {
                        bootbox.alert ("<div class='alert alert-error'>Bind to Active Directory failed. Check the login credentials and or server details. AD said: Can't contact LDAP server</div>");
                    }
              });
            return false; 
    });



});


       


