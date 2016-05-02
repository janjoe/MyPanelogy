<section class="container w90_per">
    <div class="box w98_per effect7">
        <h3>Frequently Asked Questions (FAQ)</h3>
        <p style="display: inline-block">
        <table width="100%">
            <tr>
                <td>
                    <?php
                    $sql = "select * from {{cms_page_master}} where page_name = 'FAQ'";
                    $uresult = Yii::app()->db->createCommand($sql)->query();
                    $count = $uresult->rowCount;
                    if ($count > 0) {
                        $uresult = $uresult->readAll();
                        $sql_content = "select page_content from {{cms_page_content}} where page_id = '" . $uresult[0]['page_id'] . "' and language_code = '" . Yii::app()->lang->langcode . "'";
                        $sql_content = "select page_content from {{cms_page_content}} where page_id = '" . $uresult[0]['page_id'] . "' and language_code = '" . Yii::app()->lang->langcode . "'";
                        $result = Yii::app()->db->createCommand($sql_content)->query()->readAll();
                        echo $result[0]['page_content'];
                    } else {
                        echo 'No Help To Display';
                    }
                    ?>
                </td>
            </tr>
        </table>
        </p>
    </div>
</section>