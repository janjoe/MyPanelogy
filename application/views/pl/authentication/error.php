<section class="container w100_per cen">
    <div class="box w45_per hauto effect7">
        <h3>Recover your password</h3>
        <h5><?php echo $errormsg; ?></h5>
        <p>
            <a href='<?php echo $this->createUrl("/?pagename=Login"); ?>'><?php $clang->eT("Try again"); ?></a>
            <br /> <br/>
            <a href='<?php echo $this->createUrl("/?pagename=Forgot Password"); ?>'><?php $clang->eT("Forgot your password?"); ?></a><br />
        </p>
    </div>
</section>
