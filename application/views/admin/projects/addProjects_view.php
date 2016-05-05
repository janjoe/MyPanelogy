<div class='header ui-widget-header'><?php $clang->eT("Add Projects"); ?></div>
<br />
<?php echo CHtml::form(array("admin/projects/add"), 'post', array('class' => 'form30', 'id' => 'newprojectform')); ?>

<ul>
    <li>
        <label for='project_name'><?php $clang->eT("Project Name:"); ?></label>
        <input type='text' id='project_name' name='project_name' required="required"/>
    </li>
    <li>
        <label for='project__friendly_name'><?php $clang->eT("Project Friendly Name:"); ?></label>
        <input type='text' id='project__friendly_name' name='project__friendly_name' required="required"/>
    </li>
    <li>
        <label for='parent_project'><?php $clang->eT("Parent Project:"); ?></label>
        <select name='parent_project' id='parent_project'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='client'><?php $clang->eT("Client:"); ?></label>
        <select name='client' id='client'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='client_proj'><?php $clang->eT("Client Proj.#"); ?></label>
        <input type='text' id='client_proj' name='client_proj' required="required"/>
        &nbsp;&nbsp;<label for='client_proj'><?php $clang->eT("Add to invoice:"); ?></label>&nbsp;&nbsp;<input type='text' id='add_invoice' name='add_invoice' required="required"/>
    </li>
    <li>
        <label for='po_ord'><?php $clang->eT("IO/PO/ORD#"); ?></label>
        <input type='text' id='po_ord' name='po_ord' required="required"/>
        &nbsp;&nbsp;<label for='normal_fmt'><?php $clang->eT("Normal Format:"); ?></label>&nbsp;&nbsp;<input type='text' id='normal_fmt' name='normal_fmt' required="required"/>
    </li>
    <li>
        <label for='client_contact'><?php $clang->eT("Client Contact:"); ?></label>
        <select name='client_contact' id='client_contact'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='project_manager'><?php $clang->eT("Project Manager"); ?></label>
        <select name='project_manager' id='project_manager'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='sales_person'><?php $clang->eT("Sales Person:"); ?></label>
        <select name='sales_person' id='sales_person'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='country'><?php $clang->eT("Country:"); ?></label>
        <?php
        // to do after contact complete
//        $region = Region::model()->findAll();
//        $reglist = CHtml::listData($region, 'countries_id', 'countries_name');
//        echo CHtml::dropDownList('Region', 'countries_id', $reglist, array('prompt' => 'Select Region...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
        ?>
    </li>
    <li>
        <label for='segment'><?php $clang->eT("Segment:"); ?></label>
        <input type='text' id='segment' name='segment' required="required"/>
    </li>
    <li>
        <label for='segment'><strong><?php $clang->eT("Set Target:"); ?></strong></label>
        <table width="60%" class="InfoForm">
            <tr class="odd">
                <td>
                    <input type="radio" name="settarget" value="">B2B
                    <input type="radio" name="settarget" value="">Consumer
                    <input type="radio" name="settarget" value="0">Community
                </td>
            </tr>
            <tr class="even">
                <td>
                    <div id="businessdiv" style="display: none;">
                        <input type="checkbox" name="7[]" id="7"  value="167">Accounting&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7"  value="168">Advertising&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7"  value="169">Agriculture/Forestry&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7"  value="170">Architecture&nbsp;&nbsp;<input type="checkbox" name="7[]" id="7"  value="268">NEED MORE!!&nbsp;&nbsp;
                    </div>
                    <div id="consumerdiv" style="display: none;">
                        <input type="checkbox" name="8[]" id="8"  value="171">Illness&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8"  value="172">Geo Specific&nbsp;&nbsp;<input type="checkbox" name="8[]" id="8"  value="173">Insurance/Brockers&nbsp;&nbsp;
                    </div>
                    <div id="communitydiv" style="display: none;">
                    </div>
                </td>
            </tr>
        </table>
    </li>
    <li>
        <label for='quota'><?php $clang->eT("Req. Completes:"); ?></label>
        <input type='text' id='Quota' name='quota' required="required"/>&nbsp;(Must be between 1 to 99,999)
    </li>
    <li>
        <label for='MaxCompletes'><?php $clang->eT("Max. Completes:"); ?></label>
        <select name='MaxCompletes' id='MaxCompletes'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='ppc'><?php $clang->eT("CPC $:"); ?></label>
        <input type='text' id='ppc' name='ppc' required="required"/> (Must be between $0.1 to $1,000)
    </li>
    <li>
        <label for='loi'><?php $clang->eT("LOI:"); ?></label>
        <input type='text' id='segment' name='loi' required="required"/>
    </li>
    <li>
        <label for='ir'><?php $clang->eT("IR:"); ?></label>
        <input type='text' id='ir' name='ir' required="required"/> % (Must be between 1 to 100)
    </li>
    <li>
        <label for='points'><?php $clang->eT("# of points to award :"); ?></label>
        <input type='text' id='segment' name='points' required="required"/> (The $ equivalent  100= $1)
    </li>
    <li>
        <label for='d_points'><?php $clang->eT("# of points to award - terms? :"); ?></label>
        <input type='text' id='segment' name='d_points' required="d_points"/>
    </li>
    <li>
        <label for='surveylink'><?php $clang->eT("Survey Link:"); ?></label>
        <input type='text' id='surveylink' name='surveylink' required="required"/>
    </li>
    <li>
        <label for='group_description'><?php $clang->eT("Description:"); ?></label>
        <textarea cols='50' rows='4' id='group_description' name='group_description'></textarea>
    </li>
    <li>
        <label for='status'><?php $clang->eT("Status:"); ?></label>
        <select name='status' id='status'>
            <option value="-1">Please choose... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
        </select>
    </li>
    <li>
        <label for='multiple_invoices'><?php $clang->eT("Multiple Invoices :"); ?></label>
        <select name="multiple_invoices">
            <option value="NO">NO</option>
            <option value="YES">YES</option>
        </select>
    </li>
</ul>
<p>
    <input type='submit' value='<?php $clang->eT("Add Project"); ?>' />
    <input type='hidden' name='action' value='projectmaster' />
</p>
</form>
