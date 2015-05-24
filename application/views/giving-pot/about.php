<?php
/** @var \Entity\GivingPot $giving_pot */
?>

<main role="main" class="giving-pot-main" id="giving-pot-about-main">
    <section class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="block">
                    <header>What is a Giving Pot?</header>
                    <p class="bold">A Giving Pot is a promotional tool that allows your customers, followers, friends, etc. to win, or earn, the ability to donate your preset funds.</p>
                    <p class="no-bottom-margin">For example, if you wanted to enable your customers to donate $10 for every $100 worth of goods they purchase from you, and you wanted to dedicate $1,000 to this promotional effort, you would:</p>
                    <ol type="1">
                        <li>Set up a Giving Pot for $1,000</li>
                        <li>Publicize the promotion</li>
                        <li>Once you have a list of the people who have won you return to your Giving Pot admin page where you input their names, emails, and how much they are entitled to donate. GiverHub does the rest! Each person will receive a GiverCard via email that will enable them to donate the amount you specify to any nonprofit they desire. Since you are still the one technically making the donation, you still get the tax deduction!</li>
                    </ol>
                    <div class="center">
                        <a href="/giving-pot/create" class="btn btn-primary">Create a Giving Pot</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php echo $giving_pot->render(); ?>
            </div>
        </div>
    </section>
</main>