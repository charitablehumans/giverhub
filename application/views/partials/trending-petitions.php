<div class="trending-petitions-block block">
    <header>
        Trending Petitions
        <?php if ($this->user && $this->user->isAdmin()): ?>
            <button type="button"
                    class="btn-hide-trending-pet-stats btn btn-default btn-xs">Hide Stats</button>
        <?php endif; ?>
        <?php $this->load->view('/partials/stat', ['name' => 'trend-pet']); ?>
    </header>
    <div class="search-trending-petitions-wrapper">
        <input type="text"
               placeholder="Search Petitions by Keyword"
               class="form-control search-trending-petitions">
    </div>
    <div id="trending-petitions-search-results" class="hide"></div>
    <div id="trending-petitions-search-loading" class="hide"><img src="/images/ajax-loaders/ajax-loader.gif" alt="Searching..."></div>
    <div id="trending-petitions-carousel"
         data-interval="12000"
         class="carousel slide <?php if (count(\Entity\ChangeOrgPetition::_getTrending()) > 5): ?>has-indicators<?php endif; ?>"
         data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php foreach(array_chunk(\Entity\ChangeOrgPetition::_getTrending(), 5) as $chunk_nr => $petitions): ?>
                <div class="item <?php if ($chunk_nr == 0): ?>active<?php endif; ?>">
                    <?php foreach($petitions as $petition): ?>
                        <?php /** @var \Entity\ChangeOrgPetition $petition */ ?>
                        <?php $this->load->view('/partials/trending-petition-item', ['petition' => $petition]); ?>
                    <?php endforeach; ?>
                 </div>
            <?php endforeach; ?>
        </div>

        <?php if (count(\Entity\ChangeOrgPetition::_getTrending()) > 5): ?>
            <a class="left carousel-control" href="#trending-petitions-carousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#trending-petitions-carousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php for($x = 0; $x < ceil(count(\Entity\ChangeOrgPetition::_getTrending()) / 5); $x++): ?>
                    <li data-target="#trending-petitions-carousel" data-slide-to="<?php echo $x; ?>" <?php if ($x == 0): ?>class="active"<?php endif; ?>></li>
                <?php endfor; ?>
            </ol>
        <?php endif; ?>
    </div>
</div>