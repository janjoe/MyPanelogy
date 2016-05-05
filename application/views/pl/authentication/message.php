<section class="container w100_per cen"  style="margin-left: 0px">
    <div class="box w95_per hauto effect7">
        <h3>Recover your password</h3>
        <p>
            <?php echo $message; ?><br />
            <br/>
            <a style="text-decoration: none" href='<?php echo $this->createUrl("/pl/authentication/sa/login"); ?>'>
                <input type="button" name="login_submit" value="<?php $clang->eT('Continue'); ?>">
            </a><br />
        </p>
    </div>
</section>



