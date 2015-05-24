<form class="bet-form" action="#">

    <p class="lead txtCntr">Step 1: Name your bet</p>
    <div class="form-group">
        <input type="text" class="form-control bet_name" name="name" placeholder="Create a name for the bet: e.g. 2016 Presidency" value="" tabindex="1">
    </div>

    <p class="lead txtCntr">Step 2: Define the terms of your bet</p>
    <div class="form-group">
                        <textarea
                            class="form-control bet_terms gh_tooltip"
                            name="terms"
                            data-placeholder="<?php echo htmlspecialchars("I, " . ($this->user ? $this->user->getName() : 'John Doe') . ", bet you that: "); ?>"
                            title="explain what has to happen, or not happen, for you to win the bet, e.g. &quot;John Smith wins the the 2018 presidential election.&quot;"
                            tabindex="2"></textarea>
    </div>

    <p class="lead txtCntr">Step 3: Bet amount</p>
    <div class="form-group">
        <input type="text" class="form-control bet_amount" name="amount" placeholder="e.g $10" value="" tabindex="3">
    </div>

    <p class="lead txtCntr">Step 4: Set a date when the winner is determined</p>
    <div class="form-group">
        <div class='input-group date' id='datetimepicker1' data-date-format="MM/DD/YY">
            <input
                type='text'
                class="form-control bet_date"
                placeholder="mm/dd/yy"
                tabindex="5"/>
            <span class="input-group-addon cursor-pointer"><img src="/assets/images/calendar-glyph.png" class="calendar-glyph" alt="Pick a Date"/></span>
        </div>
    </div>

    <p class="lead txtCntr">Step 5: Enter the non-profit that will receive your winnings</p>
    <div class="form-group charity-search-container">
        <ul class="bet_charity_chosen" style="display: none;"></ul>
        <input type="text" class="form-control bet_charity" name="charity" placeholder="Start typing a non-profit's name: e.g. An..." value="" tabindex="6">
        <ul class="bet_charity_results" style="display: none;"></ul>
    </div>
</form>