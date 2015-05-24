<section role="main">
    <div class="row">
        <label>Name: <input type="text" id="name_input"></label>
        <label>State:
            <select id="state_select">
                <option value="">-</option>
                <?php foreach(\Entity\CharityState::findAll() as $state): ?>
                    <?php /** @var \Entity\CharityState $state */ ?>
                    <option value="<?php echo $state->getId(); ?>"><?php echo htmlspecialchars($state->getName()); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button id="save_button">Save</button>
    </div>
</section>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#save_button').click(function() {
            var $this = jQuery(this);

            if ($this.attr('disabled')) {
                return false;
            }
            if (!confirm('Are you sure?')) {
                return false;
            }

            var name = jQuery('#name_input').val().trim();

            if (!name.length) {
                adminError({subject:'Need a name', msg : 'Enter a name plz ;)'});
                return false;
            }

            var stateId = jQuery('#state_select').val();
            stateId = parseInt(stateId);
            if (isNaN(stateId)) {
                adminError({subject:'Need a state', msg : 'Select a state plz ;)'});
                return false;
            }

            $this.attr('disabled', 'disabled');

            jQuery.ajax({
                url : '/admin/add_nonprofit_save',
                type : 'post',
                dataType : 'json',
                data : { name : name, stateId : stateId },
                error : function() {
                    adminError({msg : 'Request Failed'});
                },
                success : function(json) {
                    if (typeof(json.url) !== "string") {
                        if (json.e) {
                            adminError({msg : json.e});
                        } else {
                            adminError(json);
                        }
                    } else {
                        console.log('x' + json.url);
                        window.location = json.url;
                    }
                },
                complete : function() {
                    $this.removeAttr('disabled');
                }
            });
        });
    });
</script>