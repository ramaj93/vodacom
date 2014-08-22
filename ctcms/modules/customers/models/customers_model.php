<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/*
  | -----------------------------------------------------
  | PRODUCT NAME: 	STOCK MANAGER ADVANCE
  | -----------------------------------------------------
  | AUTHER:			MIAN SALEEM
  | -----------------------------------------------------
  | EMAIL:			saleem@tecdiary.com
  | -----------------------------------------------------
  | COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
  | -----------------------------------------------------
  | WEBSITE:			http://tecdiary.net
  | -----------------------------------------------------
  |
  | MODULE: 			Customers
  | -----------------------------------------------------
  | This is customers module's model file.
  | -----------------------------------------------------
 */

class Customers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllCustomers() {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function customers_count() {
        return $this->db->count_all("customers");
    }

    public function getLastSerial()
    {
        $this->db->select_max('name');
        $q = $this->db->get('customers');
        return $q->row();
        
    }
    public function fetch_customers($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get("customers");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getCustomerByID($id) {

        $q = $this->db->get_where('customers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCustomerByEmail($email) {

        $q = $this->db->get_where('customers', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCustomerContacts($id = NULL)
    {
        if($id != NULL){
            $q = $this->db->get_where('contacts',array('customer_id'=>$id));
            if($q->num_rows()>0){
                return $q->result();
            }
        } 
        return FALSE;
    }
    public function addCustomer($data = array()) {

        // Customer data
        $customerData = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'phone' => $data['phone']
        );

        if ($this->db->insert('customers', $customerData)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateCustomer($id, $data = array()) {

        // Customer data
        $customerData = array(
            'name' => $data['name'],
            'company' => $data['company'],
            'address' => $data['address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'phone' => $data['phone'],
        );

        $this->db->where('id', $id);
        if ($this->db->update('customers', $customerData)) {
            if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
                $logData = array(
                    'user_id' => $this->session->userdata['user_id'],
                    'record_type' => 2,
                    'record_id' => $id,
                    'altered' => 2
                );
                $this->db->insert('logs', $logData);
            }
            return true;
        } else {
            return false;
        }
    }

    public function add_customers($data = array()) {

        if ($this->db->insert_batch('customers', $data)) {
            if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
                $logData = array(
                    'user_id' => $this->session->userdata['user_id'],
                    'record_type' => 1,
                    'record_id' => $this->db->insert_id(),
                    'altered' => 1
                );
                $this->db->insert('logs', $logData);
            }
            return true;
        } else {
            return false;
        }
    }

        public function getContactByID($cst_id) {
        //var_dump($cst_id);
        //var_dump($id);
        $q = $this->db->get_where('contacts', array('customer_id' => $cst_id));
        if ($q->num_rows()) {

            return $q->result();
        }

        return FALSE;
    }
    
    public function deleteCustomer($id) {
        if ($this->db->delete('customers', array('id' => $id)) && $this->db->delete('branches', array('customer_id' => $id))) {
            if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
                $logData = array(
                    'user_id' => $this->session->userdata['user_id'],
                    'record_type' => 2,
                    'record_id' => $id,
                    'altered' => 2
                );
                $this->db->insert('logs', $logData);
            }
            return true;
        }
        return FALSE;
    }

    public function getCustomerNames($term) {
        $this->db->select('name');
        $this->db->like('name', $term, 'both');
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

}
