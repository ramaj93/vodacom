<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messaging_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUnreadMsg() {
        $id = $this->session->userdata['user_id'];
        $q = $this->db->get_where('messages', array('to' => $id, 'read' => 0));
        if ($q->num_rows() > 0) {
            return $q->num_rows();
        }
        return 0;
    }

    public function clearMessages($data) {
        $str = '';
        $interval = $data['interval'];
        //var_dump($interval);
        switch ((int)$interval) {
            case 1:
                $str = "DATE(messages.time) < date_sub( now() , INTERVAL 1 MONTH )";
                break;
            case 2:
                $str = "DATE(messages.time) < date_sub( now() , INTERVAL 2 MONTH )";
                break;
            case 3:
                $str = "DATE(messages.time) < date_sub( now() , INTERVAL 3 MONTH )";
                break;
            case 4:
                $str = "DATE(messages.time) < date_sub( now() , INTERVAL 4 MONTH )";
                break;
            case 5:
                $str = "DATE(messages.time) < date_sub( now() , INTERVAL 5 MONTH )";
                break;
            case 6:
                if ($this->db->delete('messages')) {
                    return TRUE;
                }
                return FALSE;
        }
        //var_dump($str);
        //return;
        if($this->db->where($str)
                ->delete('messages')){
            return TRUE;
        }
        return FALSE;
    }

    public function clearUnread() {
        $data = array('read' => 1);
        //var_dump($data);
        $id = $this->session->userdata['user_id'];
        $this->db->where(array('to' => $id, 'read' => 0));
        $this->db->update('messages', $data);
    }

    public function getTotalMessages() {
        $q = $this->db->get('messages');
        return $q->num_rows();
    }

    public function deleteMessage($mid = NULL) {
        $id = $this->session->userdata['user_id'];
        if ($mid != NULL) {
            $q = $this->db->select('to,from')
                    ->where('id', $id)
                    ->get('messages')
                    ->row();
            if ($q->to == $id) {
                $data = array('to' => 0);
            } else {
                $data = array('from' => 0);
            }
            $this->db->where(array('id' => $mid));
            if ($this->db->update('messages', $data)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function activateUser() {
        $id = $this->session->userdata['user_id'];
        $this->db->where(array("id" => $id));
        $this->db->update('users', array("active" => 1));
        $this->session->set_userdata(array('active' => 1));
    }

    public function isActive() {
        $this->select('active');
        $id = $this->session->userdata['user_id'];
        $q = $this->db->get_where('users', array('id' => $id));

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function inactivateUser() {
        $id = $this->session->userdata['user_id'];
        $this->db->where('id', $id);
        if ($this->db->update('users', array('active' => 0))) {
            $this->session->set_userdata(array('active' => 0));
            return TRUE;
        }
        return FALSE;
    }

    public function getMessage($uid = NULL, $mid = NULL) {
        if ($uid == NULL) {
            $uid = $this->session->userdata['user_id'];
        }
        if ($mid != NULL) {
            //var_dump($uid);
            $this->db->select('message,from');
            $this->db->where(array('to' => $uid, 'id' => $mid));
            $q = $this->db->get('messages');
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        }
        return FALSE;
    }

    public function getMessages($uid = NULL, $mid = NULL) {
        if ($uid == NULL) {
            $uid = $this->session->userdata['user_id'];
        }
        if ($mid == NULL) {
            //var_dump($uid);
            $this->db->select('u1.username as from,u2.user');
            $this->db->where('from', $uid)
                    ->or_where('to', $uid);
            $q = $this->db->get('messages');
            if ($q->num_rows() > 0) {
                return $q->result();
            }
        }
        return FALSE;
    }

    public function sendMessage($data) {
        $msgData = array(
            'from' => $data['from'],
            'to' => $data['to'],
            'message' => $data['message']
        );

        if ($this->db->insert('messages', $msgData)) {
            if (ENABLE_LOGGING && ENABLE_LOGGING_MSG) {
                $logData = array(
                    'user_id' => $data['from'],
                    'record_type' => 4,
                    'record_id' => $this->db->insert_id(),
                    'altered' => 4
                );
                $this->db->insert('logs', $logData);
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getUserByID($id) {

        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

}

/* End of file biller_model.php */ 
/* Location: ./sma/modules/billers/models/billers_model.php */