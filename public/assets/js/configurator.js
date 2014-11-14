/* Write Bacual Configuration */
function restartBacula()
{
    $.ajax({
            type: "GET",
            url: "restartbacula",
        }).done(function( msg ) {
             bootbox.alert(msg.html);
        });
}

/* Write Bacual Configuration */
function writeBacula(type)
{
    $.ajax({
            type: "GET",
            url: "writebacula",
            data: ({
              'type': type
            }),
        }).done(function( msg ) {
             bootbox.alert(msg.html);
        });
}

$(document).ready(function() {

    $(document).on("eldarion-ajax:success", function(evt, $el) {
       tree.reload();
    });

    $("#refreshTree").click(function(){
       $("input[name=search]").val("");
       $("span#matches").text("");
        tree.clearFilter();
        tree.reload();
    });


    $("#readBacula").click(function(){
       $.ajax({
            type: "GET",
            url: "readbacula",
        }).done(function( msg ) {
            tree.reload();
            bootbox.alert(msg.html);
        });
    });

   var tree = $("#tree").fancytree({
      extensions: ["filter","persist"],
      filter: {
        mode: "hide"
      },
      persist: {
        expandLazy: true,
        overrideSource: false, // true: cookie takes precedence over `source` data attributes.
        store: "auto" // 'cookie', 'local': use localStore, 'session': sessionStore
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
function deleteitem(item,id)
{
  $.ajax({
      type: "POST",
      data: ({
              'parent': item,
              'id':id
            }),
      url: "deleteitem",
      datatype: "html",
  }).done(function( data ) {
      $('#nodeDetails').empty();
      $("#tree").fancytree("getTree").reload();
  });
}
function newitem(item)
{
  $.ajax({
      type: "POST",
      data: ({
              'parent': item
            }),
      url: "newitem",
      datatype: "html",
  }).done(function( data ) {
      $('#nodeDetails').empty().html(data);
      if (item="Filesets") { $("#includeexclude").hide(); }
      if (item="Schedules") { $("#schedulerun").hide(); }
  });
}
