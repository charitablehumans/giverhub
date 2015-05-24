<div class="row">
    <div class="column large-10">
        <h3>Add FAQ</h3>
    </div>
    <div class="column large-2">
        <a class="button tiny right" href="/admin/faqs">Back to FAQs</a>
    </div>
</div>
<div class="row">
    <div class="column large-12">
        <?php echo form_open_multipart('admin/add_faq');?>

            <?php if (isset($message)): ?>
                <div data-alert class="alert-box warning radius">
                    <?php echo $message;?>
                    <a href="#" class="close">&times;</a>
                </div>
            <?php endif; ?>

            <table style="width: 100%">
                <tbody>
                     <tr>
                        <td>
                            Question
                        </td>
                        <td>
                            <input type="text" name="fq_ques" >
                            <div class="error"><?php echo form_error('fq_ques'); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Answer
                        </td>
                        <td>
                            <textarea name="fq_ans" cols="35" rows="7"></textarea>
                            <div class="error"><?php echo form_error('fq_ans'); ?></div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Filters :
                        </td>
                        <td>
                            <select style="height:100%;" id="fq-filter" name="fq_filter[]" multiple data-customforms="disabled">
                                <option value="donation">Donations</option>
                                <option value="charity">Charities</option>
                                <option value="giverhub">Giver Hub</option>
                            </select>
                            <div class="error"><?php echo form_error('fq_filter'); ?></div>
                        </td>
                    </tr>

                     <tr>
                         <td>
                             Order :
                         </td>
                         <td>
                             <select style="width: 15%" name="fq_order">
                                 <option value="<?php echo $faq_count; ?>">--Select--</option>
                                <?php
                                    for($i=1;$i<=$faq_count;$i++):
                                ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                    endfor;
                                 ?>
                             </select>
                         </td>
                     </tr>

                    <tr>
                        <td colspan="2"><input class="button tiny" type="submit" value="Add"</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>	
</div>