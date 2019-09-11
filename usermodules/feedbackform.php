<?php
if (!defined('included')) {
    exit();
}
$formShow = true;

?>

<?php
if ($formShow != false) {
    ?>

    <div class="contact-1 sidebar-widget">
        <div class="main-title-2">
            <h1><span>Leave</span> a Comment</h1>
        </div>
        <div class="contact-form">
            <form id="contact_form" name="contact_form" action="javascript:void(0)" method="post">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group fullname">
                            <input type="text" name="name" id="name" value="" class="input-text" placeholder="Full Name">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group enter-email">
                            <input type="email" name="email" id="email" value="" class="input-text" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group number">
                            <input type="number" name="phone" id="phone" value="" class="input-text" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group number">
                            <select class="input-text" id="rating" name="rating">
                                <option value="">Rating</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                        <div class="form-group message">
                            <textarea class="input-text" name="message" value="" id="message" placeholder="Write message"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php echo $chk->showCaptcha(); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        <div class="send-btn mb-0">
                            <button type="submit" onclick="savedata();" name="Submit" class="btn-md btn-theme">Send Message</button>
                            <input name="pagelink" type="hidden" id="pagelink" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php } ?>


<style type="text/css">
    .postlink{
        font-size: 16px;
        border: solid 1px #dc4201;
        border-radius: 8px;
        padding: 4px 10px;
        background: #f04a04;
        color: #fff;
    }
</style>

<script>
    function savedata() {

        $("#contact_form").one("submit", function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                cache: false,
                async: true,
//                url: '<?php// echo ROOT_PATH; ?> + "usermodules/feedbackform_edit.php"',
                url:"usermodules/feedbackform_edit.php",
                data: $(this).serializeArray(),
                success: function (data) {
                    console.log('sagar');
                    console.log(data);
                    
                    
                    $("#name").val("");
                    $("#email").val("");
                    $("#phone").val("");
                    $("#rating").val("");
                    $("#message").val("");
                }
            });
            return false;
        });
    }
    function displaytoggle(div) {
        if ($('#' + div).css('display') != 'none')
        {
            $('#' + div).slideUp();
        } else {
            $('#' + div).slideDown();
        }
    }

</script>


