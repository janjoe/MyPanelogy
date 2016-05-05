<script>
    function chngattribute(){
        var checkedCheckboxes = $('input[name="<?php echo $que_id; ?>[]"]:checked'); 
        var checkboxes = $('input[name="<?php echo $que_id; ?>[]"]'); 
        if(checkedCheckboxes.length) {
            checkboxes.removeAttr("required");
        } else {
            checkboxes.attr("required", "required");
        }
    }
</script>