<?php
        class Account_model extends CI_Model {

        public $title;
        public $content;
        public $date;



        public function get_data_where($table,$where,$whereval)
        {
                $this->db->where($where,$whereval);
                $query=$this->db->get($table);
                //$query = $this->db->get('entries', 10);
                return $query->row_array();
        }
        public function get_account_by_username($username)
        {

                $q="SELECT * FROM signup WHERE (Username = '$username' OR Email= '$username') AND type != 'shop'";
                $query = $this->db->query($q);
                //return $query->row_array();
                return $query->result_array();
        }


        public function check_login($username,$password)
        {

                $q="SELECT * FROM signup WHERE (Username= '".$username."' OR Email= '".$username."' ) AND Password= '".$password."' AND type != 'shop'";

                $query = $this->db->query($q);
                return $query;
        }

        public function get_user_package($sid)
        {

                $q="SELECT package,Username,tree,local_transfar FROM signup WHERE id= $sid";
                $query = $this->db->query($q);
                return $query->row_array();
        }

        public function insert_entry($table,$array)
        {
                $this->db->insert($table, $array);
                return $insert_id = $this->db->insert_id();

        }

        public function insert_multiple_entries($table,$array)
        {
               return  $this->db->insert_batch($table, $array);
                //return $insert_id = $this->db->insert_id();

        }

        // public function update_entry()
        // {
        //         $this->title    = $_POST['title'];
        //         $this->content  = $_POST['content'];
        //         $this->date     = time();

        //         $this->db->update('entries', $this, array('id' => $_POST['id']));
        // }

        }
?>
