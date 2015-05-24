<?php

class MY_Session extends CI_Session {


    /* disable regenerate session id to prevent logging people out
(google codeigniter session race condition) ...
probably a small security issue, but better then having users signed out all the time. */

    /**
     * Update an existing session
     *
     * @access	public
     * @return	void
     */
    function sess_update()
    {
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
        {
            return;
        }

        $this->userdata['last_activity'] = $this->now;

        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;

        // Update the session ID and last_activity field in the DB if needed
        if ($this->sess_use_database === TRUE)
        {
            // set cookie explicitly to only have our session data
            $cookie_data = array();
            foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
            {
                $cookie_data[$val] = $this->userdata[$val];
            }

            $this->CI->db->query(
                         $this->CI->db->update_string(
                                      $this->sess_table_name,
                                      array('last_activity' => $this->now),
                                      array('session_id' => $this->userdata['session_id'])
                         )
            );
        }

        // Write the cookie
        $this->_set_cookie($cookie_data);
    }
}