<?php
if (!$this->session->userdata('learn_more')) {
    $this->session->set_userdata('learn_more', true);
    $this->load->view('/partials/learn-more/bets');
} else {
    $this->session->set_userdata('learn_more', false);
    $this->load->view('/partials/learn-more/givercards');
}