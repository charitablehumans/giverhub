<form class="navbar-form" role="search" method="post" action="/search">
    <div class="input-group">
        <input
            type="text"
            class="form-control gh_general_search_field"
            dplaceholder="Search Nonprofits and Petitions"
            name="search_text"
            id="srch-term"
            value="<?php echo htmlspecialchars(@$_POST['search_text']); ?>"
            autocomplete="off">
        <button class="btn-top-search-submit btn btn-success rounded" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        <div class="search-placeholder"><span>Search</span> Nonprofits and Petitions</div>
    </div>
</form>