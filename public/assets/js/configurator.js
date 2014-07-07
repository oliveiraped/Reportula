$(document).ready(function() {
    
    $("#readBacula").click(function(){
       $.ajax({
            type: "GET",
            url: "readbacula",
        }).done(function( msg ) {
            alert( msg );
        });
    });

    $("#tree").fancytree({
      extensions: ["filter"],
      filter: {
        mode: "hide"
      },

      source: {
        url: "gettreedata",
        cache: false
      },
      autoScroll: true,
      focus: function(event, data){
        data.node.scrollIntoView(true);
      },
       init: function(event, data) {
        data.tree.getFirstChild().setFocus();
      },
     
      beforeSelect: function(event, data){
          // A node is about to be selected: prevent this for folders:
        if( data.node.isFolder() ){
          return false;
        }
      },
     activate: function(event, data){
       if( data.node.isFolder() ){
          return false;
        }
          /* Ajax to Get Options Selected Node */
          $.ajax({
              type: "POST",
              data: ({ 
                      'node': data.node.title, 
                      'parent': data.node.parent.title 
                    }),
              url: "getnode",
              datatype: "html",
          }).done(function( data ) {
              $('#nodeDetails').empty().html(data);
          });
      },

    });
 
  var tree = $("#tree").fancytree("getTree");

  $("input[name=search]").keyup(function(e){
      var n, leavesOnly = $("#leavesOnly").is(":checked"), match = $(this).val();

      if(e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === ""){
        $("button#btnResetSearch").click();
        return;
      }
      if($("#regex").is(":checked")) {
        // Pass function to perform match
        n = tree.filterNodes(function(node) {
          return new RegExp(match, "i").test(node.title);
        }, leavesOnly);
      } else {
        // Pass a string to perform case insensitive matching
        n = tree.filterNodes(match, leavesOnly);
      }
      $("button#btnResetSearch").attr("disabled", false);
      $("span#matches").text("(" + n + " matches)");
    }).focus();



});