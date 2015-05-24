<?php $CI =& get_instance(); ?>
<section class="modal-body loading"><p class="lead txtCntr">Loading payment methods.</p><img alt="Loading" src="/images/loading.gif"></section>
<section class="modal-body not-loading">
    <p class="lead txtCntr" data-non-instant-donations-text="Please Select Your Payment Method" data-instant-donations-text="Please Select Your Instant Donations Payment Method"></p>
    <div class="saved-payment-methods-loading"><img alt="Loading" src="/images/loading.gif"><hr/></div>
    <ul class="saved-payment-methods"></ul>

    <!-- see card/card.js -->
    <div id="card-wrapper"></div>

    <form id="frm-add-new-card" class="new-payment-method" action="#">
        <div class="form-group">
            <label for="newCardNumber">Card Number: </label>
            <input class="form-control" type="text" id="newCardNumber" name="newCardNumber" placeholder="Card Number: ">
            <input type="hidden" id="newCardType" name="newCardType" value="">
        </div>

        <div class="form-group">
            <label for="newCardholderName">Cardholder Name: </label>
            <input class="form-control" type="text" id="newCardholderName" name="newCardholderName" placeholder="Cardholder Name: ">
        </div>

        <div class="form-group expiry">
            <label for="newExpiryMonth">Expiration Date: </label>
            <select class="form-control" id="newExpiryMonth" name="newExpiryMonth">
                <option value="">Month:</option>
                <option value="01">01/Jan</option>
                <option value="02">02/Feb</option>
                <option value="03">03/Mar</option>
                <option value="04">04/Apr</option>
                <option value="05">05/May</option>
                <option value="06">06/Jun</option>
                <option value="07">07/Jul</option>
                <option value="08">08/Aug</option>
                <option value="09">09/Sep</option>
                <option value="10">10/Oct</option>
                <option value="11">11/Nov</option>
                <option value="12">12/Dec</option>
            </select>
            <select class="form-control" name="newExpiryYear" id="newExpiryYear">
                <option value="">Year:</option>
                <?php foreach (range(date('Y'), date('Y') + 20) as $year): ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group security">
            <label>Security: </label>
            <input class="form-control" type="text" id="newSecurityCode" name="newSecurityCode" placeholder="Security Code: ">
        </div>

        <div id="add-new-card-alert-success" class="alert alert-success add-new-card-alert hide">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span><strong>Great!</strong> Your card was added and made your default payment method.<!--To change your default payment method select the desired card above--></span>
        </div>

        <div id="add-new-card-alert-danger" class="alert alert-danger add-new-card-alert hide">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span id="add-new-card-alert-danger-msg">Unknown error</span>
        </div>
    </form>
    <hr/>
    <span class="addresses-header">Address(es)</span>
    <hr/>
    <div class="addresses-container" data-address-container-from="change-payment" id="payment-method-addresses">
        <?php $CI->load->view('partials/_addresses'); ?>
    </div>
    <hr/>
    <a class="btn btn-primary btn-add-address-from-payment-methods btn-sm" href="#">Add Address</a>
</section>