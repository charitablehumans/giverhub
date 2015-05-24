<?php
$CI =& get_instance();
?>
<section class="gh_secondary_header clearfix">
    <section class="container empty_header">
        <div class="row">
			
            <h2 class="col-md-12 blog-page-big-heading gh_spacer_21">The GiverHub Blog</h2>
			<?php if ($CI->user && $CI->user->getLevel() >= 4): ?>
			<div class="col-md-12">
				<button data-toggle="modal" class="btn btn-default btn-primary" data-target="#blog-modal"><span class="glyphicon glyphicon-plus"></span> create new post</button>
			</div>
			<?php endif; ?>

        </div><!-- row end -->
    </section><!-- empty_header end -->
</section>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
			<div id="" class="blog-block block">
			<?php if ($blogs) : 
				foreach($blogs as $giverhubPosts) : ?>
                <div class="blog-content">
					<h3 class="blog_title"><?php echo $giverhubPosts->getTitle(); ?></h3>
					<div class="col-md-12 clearfix gh_spacer_7">
						<div class="blog-written-by">Written by</div>
						<div class="blog-written-by-img">
								<img src="<?php echo $giverhubPosts->getUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($giverhubPosts->getUser()->getName()); ?>">
				                <span class="name"><?php echo htmlspecialchars($giverhubPosts->getUser()->getName()); ?></span>
						</div>
						<div class="blog-written-by">
						(published: <?php echo date('H:i:s, d/m/y',strtotime($giverhubPosts->getCreatedAt())); ?>)</div>
					</div>
					<div class="col-md-12 blog-description"><?php echo $giverhubPosts->getDescription(); ?></div>

		        </div>
			<?php endforeach; 
			else: ?>
				<div class="no-blog-found"><h3>No Blogs Found!</h3></div>
			<?php endif; ?>
</div>
            
        </div>
        
    </section><!-- container end -->
</main>


<!-- Add blog modal -->
<div class="modal fade" id="blog-modal" tabindex="-1" role="dialog" aria-labelledby="blog-modal" aria-hidden="true">
    <div class="col-md-4 col-md-offset-4">
        <div class="modal-content">

			<header class="modal-header clearfix">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="icon-remove-sign"></i></button>
            </header>

            <section class="modal-body clearfix">
                <div class="col-md-12">
                    <form class="form-horizontal add-blog-form" role="form" name="add-blog-form" method="post">
						<div class="row">
							<input type="text" class="form-control gh_spacer_14" name="blog_title" id="blog_title" placeholder="Insert title">
							<textarea class="form-control gh_spacer_21" rows="10" name="blog_description" id="blog_description" placeholder="Type body of blog post here"></textarea>
						</div>
						<div class="row pull-right gh_spacer_7">
							<button class="btn btn-default btn-primary btn-save-blog-draft" id="save_blog" type="submit" value="0">save as draft</button>
							<button class="btn btn-default btn-success btn-save-blog-publish" id="save_blog" type="submit" value="1">PUBLISH</button>
						</div>
					</form>
                </div>
            </section>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

