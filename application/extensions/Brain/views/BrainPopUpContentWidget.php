<script type="text/javascript">
    $(document).ready(function() {

        var php_box_id = <?php echo json_encode($popup_box_id) ?>;
        var php_link_id = <?php echo json_encode($popup_link_id) ?>;
        var container_id = <?php echo json_encode($container_id) ?>;
        var popup_on_load = <?php echo json_encode($popup_on_load) ?>;
        var popup_title = <?php echo json_encode($popup_title) ?>;
        var uid = <?php echo json_encode($uid) ?>;

        var box_close_id = "close_" + php_box_id;

        $('#' + php_box_id).hide();
        
        //on click the container close the popup
        //$('#'+container_id).click( function() {
        //    unloadPopupBox();
        //});

        // When site loaded, load the Popupbox First
        if (popup_on_load == 'true') {
            loadPopupBox();
        }
        
        $('#' + php_link_id).click(function() {
            loadPopupBox();  // function load popup
        });

        //adding close button and h1 title
        $('#' + php_box_id).prepend("<a id='" + box_close_id + "' class='popupBoxClose'></a><h1 id='popup_title' class='popup_title'>" + popup_title + "</h1>");
        $('#' + box_close_id).click(function() {
            unloadPopupBox();
        });

        function loadPopupBox() {    // To Load the Popupbox
            //adding id text box
            $('#' + php_box_id).prepend("<input type='hidden' name='__txtnid' id='__txtid' value='<?php echo $uid; ?>' />");
            $('#' + container_id).addClass("container");
            $('#' + php_box_id).addClass("popup_box");
            $('#' + php_box_id).fadeIn("slow");
            $('#' + container_id).css({"opacity": "0.3"});
        }

        function unloadPopupBox() { // TO Unload the Popupbox
            $('#' + php_box_id).fadeOut("slow");
            $('#' + container_id).css({"opacity": "1"});
            $('#__txtid').remove();  //removing txt id textbox
            //$(this).parents('#__txtid').remove();
        }
        
        $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            unloadPopupBox();
        }
});

    });
</script> 

<style>
    .popup_box{ 
        height:<?php echo $height; ?>;
        width:<?php echo $width; ?>;
        top:50%;
        left:50%;
        margin-left:-<?php echo $width / 2; ?>px;   /* negative half of width above */
        margin-top:-<?php echo $height / 2; ?>px;   
    }
</style>
