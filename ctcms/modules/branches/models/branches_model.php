<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branches_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllCustomers() {
        //$this->db->where('customers');
        $q = $this->db->get('customers');
        if ($q->num_rows()) {
            return $q->result();
        }
        return FALSE;
    }

    public function getTechnologyByID($id) {
        $q = $this->db->get_where('technologies', array('id' => $id));
        if ($q->num_rows()) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBranchByID($cid, $brid) {
        //echo 'cid='.$cid . ' brid='.$brid;
        $q = $this->db->get_where('branches', array('id' => $brid, 'customer_id' => $cid), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getUserGroup($id) {
        $id || $this->session->userdata['user_id'];
        return $this->db->get_where('users_groups', array('user_id' => $id), 1)->row();
    }

    public function getWarehouseByID($id) {
        if ($id != NULL) {
            $q = $this->db->get_where('customers', array('id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        }

        return FALSE;
    }

    public function getAllBandwidths() {
        $q = $this->db->get('bandwidth');
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
    }

    public function getAllTechnologies() {

        $q = $this->db->get('technologies');
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
    }

    public function addBranch($data) {
        $noerror = FALSE;
        $branchData = array(
            'customer_id' => $data['cst_id'],
            'branch_name' => $data['branch_name'],
            'technology_id' => $data['technology'],
            'service_id' => $data['service'],
            'traffic_pro_1' => $data['trpr1'],
            'traffic_pro_2' => $data['trpr2'],
            'mac_addr' => $data['mac_addr'],
            'bandwidth_id' => $data['bandwidth'],
            'donor_site' => $data['donor_site'],
            'region' => $data['region']
        );

        if ($this->db->insert('branches', $branchData)) {
            $insert_id = $this->db->insert_id();
            $noerror = TRUE;
            if (!$this->ion_auth->in_group('project')) {
                $brIpData = array(
                    'branch_id' => $insert_id,
                    'service_ip' => $data['serv_ip'],
                    'wan_ip' => $data['bwan_ip'],
                    'gateway_ip' => $data['bgatwy_ip'],
                    'subnet_mask' => $data['bsbn_mask'],
                    'vlan_ip' => $data['bvlan_ip']
                );
                if ($this->db->insert('branch_ip', $brIpData))
                    $noerror = TRUE;
                else
                    $noerror = FALSE;
                $manIpData = array(
                    'branch_id' => $insert_id,
                    'management_ip' => $data['serv_ip'],
                    'gateway_ip' => $data['mgatwy_ip'],
                    'subnet_mask' => $data['msbn_mask'],
                    'vlan_ip' => $data['mvlan_ip']
                );
                if ($this->db->update('management_ip', $manIpData))
                    $noerror = TRUE;
                else
                    $noerror = FALSE;
            }
            if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
                $logData = array(
                    'user_id' => $this->session->userdata['user_id'],
                    'record_type' => 1,
                    'record_id' => $insert_id,
                    'altered' => 1
                );
                $this->db->insert('logs', $logData);
            }
        }
        return $noerror;
    }

    public function updateBranch($cid, $id, $data) {
        $noerror = FALSE;
        if (!$this->ion_auth->in_group('configuration')) {
            $branchData = array(
                'customer_id' => $data['cst_id'],
                'branch_name' => $data['branch_name'],
                'technology_id' => $data['technology'],
                'service_id' => $data['service'],
                'traffic_pro_1' => $data['trpr1'],
                'traffic_pro_2' => $data['trpr2'],
                'mac_addr' => $data['mac_addr'],
                'bandwidth_id' => $data['bandwidth'],
                'donor_site' => $data['donor_site'],
                'region' => $data['region']
            );
            $this->db->where(array('id' => $id, 'customer_id' => $cid));
            if ($this->db->update('branches', $branchData)) {
                $noerror = TRUE;
            } else {
                $noerror = FALSE;
            }
        }
        if (!$this->ion_auth->in_group('project')) {
            $brIpData = array(
                'service_ip' => $data['serv_ip'],
                'wan_ip' => $data['bwan_ip'],
                'gateway_ip' => $data['bgatwy_ip'],
                'subnet_mask' => $data['bsbn_mask'],
                'vlan_ip' => $data['bvlan_ip']
            );
            $icheck = $this->db->get_where('branch_ip', array("id" => $id));
            if ($icheck->num_rows() > 0) {
                $this->db->where(array('branch_id' => $id));
                if ($this->db->update('branch_ip', $brIpData)) {
                    $noerror = TRUE;
                } else {
                    $noerror = FALSE;
                }
            } else {
                $brIpData['branch_id'] = $id;
                $this->db->insert('branch_ip', $brIpData);
            }
            $manIpData = array(
                'management_ip' => $data['serv_ip'],
                'gateway_ip' => $data['mgatwy_ip'],
                'subnet_mask' => $data['msbn_mask'],
                'vlan_ip' => $data['mvlan_ip']
            );
            $icheck = $this->db->get_where('management_ip', array("id" => $id));
            if ($icheck->num_rows() > 0) {
                $this->db->where(array('branch_id' => $id));
                if ($this->db->update('management_ip', $manIpData)) {
                    $noerror = TRUE;
                } else {
                    $noerror = FALSE;
                }
            } else {
                $manIpData['branch_id'] = $id;
                $this->db->insert('management_ip', $manIpData);
            }
        }
        if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
            $logData = array(
                'user_id' => $this->session->userdata['user_id'],
                'record_type' => 1,
                'record_id' => $id,
                'altered' => 2
            );
            $this->db->insert('logs', $logData);
        }
        return $noerror;
    }

    public function getBranchIPAddresses($id) {
        $q = $this->db->get_where('branch_ip', array('branch_id' => $id));
        if ($q->num_rows()) {

            return $q->row();
        }

        return FALSE;
    }

    public function getBranchManagementAddresses($id) {
        $q = $this->db->get_where('management_ip', array('branch_id' => $id));
        if ($q->num_rows()) {

            return $q->row();
        }

        return FALSE;
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

    public function updateProduct($id, $photo, $data = array()) {


        if ($photo == NULL) {
            // Product data
            $productData = array(
                'code' => $data['code'],
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'unit' => $data['unit'],
                'size' => $data['size'],
                'cost' => $data['cost'],
                'price' => $data['price'],
                'alert_quantity' => $data['alert_quantity'],
                'cf1' => $data['cf1'],
                'cf2' => $data['cf2'],
                'cf3' => $data['cf3'],
                'cf4' => $data['cf4'],
                'cf5' => $data['cf5'],
                'cf6' => $data['cf6']
            );
        } else {
            // Product data
            $productData = array(
                'code' => $data['code'],
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'unit' => $data['unit'],
                'size' => $data['size'],
                'cost' => $data['cost'],
                'price' => $data['price'],
                'alert_quantity' => $data['alert_quantity'],
                'cf1' => $data['cf1'],
                'cf2' => $data['cf2'],
                'cf3' => $data['cf3'],
                'cf4' => $data['cf4'],
                'cf5' => $data['cf5'],
                'cf6' => $data['cf6'],
                'image' => $photo
            );
        }


        $this->db->where('id', $id);
        if ($this->db->update('products', $productData)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id) {
        if ($this->db->delete('products', array('id' => $id)) && $this->db->delete('warehouses_products', array('product_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteBranch($cid, $brid) {
        if ($this->db->delete('branches', array('id' => $brid, 'customer_id' => $cid))) {
            if (ENABLE_LOGGING && ENABLE_LOGGING_DATA) {
                $logData = array(
                    'user_id' => $this->session->userdata['user_id'],
                    'record_type' => 1,
                    'record_id' => $brid,
                    'altered' => 3
                );
                $this->db->insert('logs', $logData);
            }
            return true;
        }
        return FALSE;
    }

    public function getAllCategories() {
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllServices() {
        $q = $this->db->get('services');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getServiceByID($id) {
        $q = $this->db->get_where('services', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCustomerByID($id) {
        $q = $this->db->get_where('customers', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
