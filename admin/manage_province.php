<?php
require_once("./../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `province_list` where province_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="department-form">
        <input type="hidden" name="id" value="<?php echo isset($province_id) ? $province_id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" name="name" autofocus autocomplete="off" id="name" required class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : '' ?>">
        </div>
        <div class="form-group">
            <label for="region_id" class="control-label">Region</label>
            <select name="region_id" id="region_id" class="form-select form-select-sm select2" required>
                <option value="" disabled <?php echo !isset($region_id) ? 'selected' : '' ?> data-placeholder="Select Region Here"></option>
                <?php 
                $region = $conn->query("SELECT * FROM `region_list` order by `name` asc ");
                while($row = $region->fetchArray()):
                ?>
                    <option value="<?php echo $row['region_id'] ?>" <?php echo isset($region_id) && $row['region_id'] == $region_id ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#department-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_province',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($province_id) ?>" != 1)
                        _this.get(0).reset();
                        $('.select2').trigger('change')
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>