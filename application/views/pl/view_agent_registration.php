<script type="text/javascript">
    function submitform(){
        $("#resendActivationcode").submit();
    }
</script>
<style type="text/css">.btn-default {
    padding: 10px 30px;
    background-color: #1d88c7;
    color: #fff;
    font-weight: 400;
    font-size: 18px;
    display: inline-block;
}</style>
<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Registered</h3>
        <p style="padding: 3%;">
           Thank you for confirming your membership and welcome to Panelogy!<br><br>
            Please click on the link below and then sign-in with your email and password you just used. Once singed in you’ll be placed into your member portal. <br><br>
            Again, thank you for your interest in Panelogy and welcome. <br/><br/>
            <a class="btn-default" href="<?php echo Yii::app()->getBaseUrl(true).'/?pagename=Login'; ?>">Sign In</a>
        </p>    
    </div>
</section>
