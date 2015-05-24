<?php /** @var \Entity\FAQ $faq */ ?>
<div class="row">
    <div class="column large-10">
        <h3>Edit FAQ</h3>
    </div>
    <div class="column large-2">
        <a class="button tiny right" href="/admin/faqs">Back to FAQs</a>
    </div>
</div>
<div class="row">
    <div class="column large-12">
        <?php echo form_open_multipart('admin/edit_faq/'.$faq->getId());?>

            <?php if (isset($message)): ?>
                <div data-alert class="alert-box warning radius">
                    <?php echo $message;?>
                    <a href="#" class="close">&times;</a>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div data-alert class="alert-box success radius">
                    <?php echo $success_message;?>
                    <a href="#" class="close">&times;</a>
                </div>
            <?php endif; ?>

            <table style="width:100%;">
                <tbody>
                     <tr>
                        <td>
                            Question
                        </td>
                        <td>
                            <input type="text" name="fq_ques"  style="width: 40%;" value="<?php echo $faq->getFqQues();?>">
                            <div class="error"><?php echo form_error('fq_ques'); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Answer
                        </td>
                        <td>
                           <textarea name="fq_ans" cols="35" rows="7" style="width: 40%;"><?php echo $faq->getFqAns();?></textarea>
                            <div class="error"><?php echo form_error('fq_ans'); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Filters :
                        </td>
                        <td>
                            <?php $filter = explode(',', $faq->getFqFilter()); ?>
                            <select id="fq-filter" name="fq_filter[]" multiple style="height: 100%;">
                                <option value="donation" <?php if(in_array('donation', $filter)) : ?> selected="selected" <?php endif; ?>>Donations</option>
                                <option value="charity" <?php if(in_array('charity', $filter)) : ?> selected="selected" <?php endif; ?>>Charities</option>
                                <option value="giverhub" <?php if(in_array('giverhub', $filter)) : ?> selected="selected" <?php endif; ?>>Giver Hub</option>
                            </select>
                            <div class="error"><?php echo form_error('fq_filter'); ?></div>
                        </td>
                    </tr>
                     <tr>
                         <td>
                             Order
                         </td>
                         <td>
                            <select id="fq-order" name="fq_order" style="width: 20%;" >
                                <?php
                                    for($i=1;$i<=$faq_count;$i++):
                                ?>
                                        <option value="<?php echo $i;?>" <?php if ($faq->getFqOrder() == $i): echo "selected"; endif; ?> ><?php echo $i;?></option>
                                <?php
                                    endfor;
                                ?>
                            </select>
                         </td>
                     </tr>

                    <tr>
                        <td colspan="2"><input class="button tiny" type="submit" value="Save!"</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>