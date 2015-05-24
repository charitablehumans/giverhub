<?php /** @var \Entity\ChangeOrgPetition[] $petitions */ ?>
<?php foreach($petitions as $petition): ?>
    <?php $this->load->view('/partials/trending-petition-item', ['petition' => $petition]); ?>
<?php endforeach; ?>