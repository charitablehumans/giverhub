<?php
/** @var \Entity\User $user */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<div class="block bet-a-friend show-petitions">
    <div class="row">
        <div class="col-xl-12 search_charity ">
            <form action="#" id="petitions_search" method="post">
                <input type="text" name="find_petitions" class="find_petitions" placeholder="Enter text here to search petitions by name or keyword" style="display: inline" value="">
            </form>
        </div>
    </div>

    <div class="petitions-feed">
        <?php
            if ($user) {
                $recommended_petitions = $user->getRecommendedPetitions(4);
            } else {
                $recommended_petitions = \Entity\ChangeOrgPetition::getFeaturedPetitions(4);
                if (count($recommended_petitions) < 4) {
                    $more_recommended_petitions = \Entity\ChangeOrgPetition::findSphinx('', 'relevance', 0, 4 - count($recommended_petitions))['content_relevance'];
                    foreach($more_recommended_petitions as $more) {
                        $recommended_petitions[] = $more;
                    }
                }
            }

            $this->load->view(
                       '/members/_member_petitions',
                           [
                               'petitions' => $recommended_petitions ? [array_values($recommended_petitions)[0]] : [],
                               'search_text' => '',
                           ]
            );
        ?>
    </div>
    <div class="petitions-feed-hover" style="display:none">
        <?php
        $this->load->view(
                   '/members/_member_petitions',
                       [
                           'petitions' => $recommended_petitions,
                           'search_text' => '',
                       ]
        );
        ?>
    </div>
</div>