<?php
        class Home_model extends CI_Model {

            public $title;
            public $content;
            public $date;

            public function get_last_ten_entries()
            {
                    $query = $this->db->get('entries', 10);
                    return $query->result();
            }

            public function get_id_fromSignup($sid)
            {
                   $query = $this->db->query("SELECT id FROM signup WHERE boss_id='$sid' OR id='$sid'");
                    return $query->row_array();
            }            

            public function get_data_by_query($CustomQuery)
            {
                   $query = $this->db->query($CustomQuery);
                    return $query->row_array();
            }

            public function get_all_data_by_query($CustomQuery)
            {
                   $query = $this->db->query($CustomQuery);
                    return $query->result_array();
            }
        }
?>