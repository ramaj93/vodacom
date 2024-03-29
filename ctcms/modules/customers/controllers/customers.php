<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customers extends MY_Controller {
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
      | This is customers module controller file.
      | -----------------------------------------------------
     */

    function __construct() {
        parent::__construct();

        // check if user logged in 
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->model('customers_model');
    }

    function index() {

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("customers");
        $data['page_title'] = $this->lang->line("customers");
        $this->load->view('commons/header', $meta);
        $this->load->view('content', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax() {

        $is_owner = $this->ion_auth->in_group('owner');
        $this->load->library('datatables');
        $this->datatables
                ->select("cu.id as id, cu.name, cu.company, co.phone, co.email, co.location, cu.country")
                ->join('contacts as co','cu.id=co.customer_id','left')
                ->from("customers as cu")
                ->group_by("cu.id");
        if ($is_owner) {
            $this->datatables->add_column("Actions", "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=customers&view=customer_details&amp;cid=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("list_contacts") . "'><i class='icon-fullscreen'></i></a><a class=\"tip\" title='" . $this->lang->line("edit_customer") . "' href='index.php?module=customers&amp;view=edit&amp;id=$1'><i class=\"icon-edit\"></i></a>
			<a class=\"tip\" title='" . $this->lang->line("delete_customer") . "' href='index.php?module=customers&amp;view=delete&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_customer') . "')\"><i class=\"icon-remove\"></i></a></center>", "id");
        } else {
            $this->datatables->add_column("Actions", "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=products&view=product_details&amp;cid=$1&amp;brid=$2', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("list_contacts") . "'><i class='icon-fullscreen'></i></a></center>");
        }

        $this->datatables->unset_column('id');


        echo $this->datatables->generate();
    }

    function customer_details($cid) {

        if ($this->input->get('cid')) {
            $cid = $this->input->get('cid');
        }
        //var_dump($cid);
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


        $customer = $this->customers_model->getCustomerByID($cid);
        $data['customer'] = $customer;
        $data['contacts'] = $this->customers_model->getContactByID($cid);
        $meta['page_title'] = $this->lang->line("customer_details");
        $data['page_title'] = $this->lang->line("customer_details");
        $this->load->view('details', $data);
    }
    function add() {
        $groups = array('owner', 'admin', 'project');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        //echo 'hello';
        $last_serial = (int) $this->customers_model->getLastSerial()->name;
        $data['serial'] = str_pad($last_serial + 1, 6, '0', STR_PAD_LEFT);
        //var_dump($data['serial']);
        //return;
        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("serial_no"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'xss_clean|min_length[9]|max_length[16]');



        if ($this->form_validation->run() == true) {
            $data = array(
                'name' => $this->input->post('serial_no'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() == true && $this->customers_model->addCustomer($data)) { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("customer_added"));
            redirect("module=customers", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $data['company'] = array('name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $data['cui'] = array('name' => 'cui',
                'id' => 'cui',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cui', '-'),
            );
            $data['reg'] = array('name' => 'reg',
                'id' => 'reg',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reg', '-'),
            );
            $data['cnp'] = array('name' => 'cnp',
                'id' => 'cnp',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cnp', '-'),
            );
            $data['serie'] = array('name' => 'serie',
                'id' => 'serie',
                'type' => 'text',
                'value' => $this->form_validation->set_value('serie', '-'),
            );
            $data['account_no'] = array('name' => 'account_no',
                'id' => 'account_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('account_no', '-'),
            );
            $data['bank'] = array('name' => 'bank',
                'id' => 'bank',
                'type' => 'text',
                'value' => $this->form_validation->set_value('bank', '-'),
            );
            $data['address'] = array('name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address'),
            );
            $data['city'] = array('name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => $this->form_validation->set_value('city'),
            );
            $data['state'] = array('name' => 'state',
                'id' => 'state',
                'type' => 'text',
                'value' => $this->form_validation->set_value('state'),
            );
            $data['postal_code'] = array('name' => 'postal_code',
                'id' => 'postal_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('postal_code'),
            );
            $data['country'] = array('name' => 'country',
                'id' => 'country',
                'type' => 'text',
                'value' => $this->form_validation->set_value('country'),
            );
            $data['phone'] = array('name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );


            $meta['page_title'] = $this->lang->line("new_customer");
            $data['page_title'] = $this->lang->line("new_customer");
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');
        }
    }

    function edit($id = NULL) {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        //validate form input
        $this->form_validation->set_rules('serial', $this->lang->line("serial"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone1', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');
         $this->form_validation->set_rules('phone2', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('serial'),
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
            );
        }

        if ($this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("customer_updated"));
            redirect("module=customers", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['customer'] = $this->customers_model->getCustomerByID($id);
            $data['contacts'] = $this->customers_model->getCustomerContacts($id);
            
            $meta['page_title'] = $this->lang->line("update_customer");
            $data['id'] = $id;
            $data['page_title'] = $this->lang->line("update_customer");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');
        }
    }

    function add_by_csv() {

        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=products', 'refresh');
        }

        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
                redirect('module=home', 'refresh');
            }

            if (isset($_FILES["userfile"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '200';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=suppliers&view=add_by_csv", 'refresh');
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('name', 'email', 'phone', 'company', 'address', 'city', 'state', 'postal_code', 'country');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->customers_model->getCustomerByEmail($csv['email'])) {
                        $this->session->set_flashdata('message', $this->lang->line("check_customer_email") . " (" . $csv['email'] . "). " . $this->lang->line("customer_already_exist") . " " . $this->lang->line("line_no") . " " . $rw);
                        redirect("module=customers&view=add_by_csv", 'refresh');
                    }
                    $rw++;
                }
            }

            $final = $this->mres($final);
            //$data['final'] = $final;
        }

        if ($this->form_validation->run() == true && $this->customers_model->add_customers($final)) {
            $this->session->set_flashdata('success_message', $this->lang->line("customers_added"));
            redirect('module=customers', 'refresh');
        } else {

            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $meta['page_title'] = $this->lang->line("add_customers_by_csv");
            $data['page_title'] = $this->lang->line("add_customers_by_csv");
            $this->load->view('commons/header', $meta);
            $this->load->view('add_by_csv', $data);
            $this->load->view('commons/footer');
        }
    }

    function delete($id = NULL) {
        if (DEMO) {
            $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
            redirect('module=home', 'refresh');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if ($this->customers_model->deleteCustomer($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("customer_deleted"));
            redirect("module=customers", 'refresh');
        }
    }

    function mres($q) {
        if (is_array($q))
            foreach ($q as $k => $v)
                $q[$k] = $this->mres($v); //recursive
        elseif (is_string($q))
            $q = mysql_real_escape_string($q);
        return $q;
    }

    function suggestions() {
        $term = $this->input->get('term', TRUE);

        if (strlen($term) < 2)
            break;

        $rows = $this->customers_model->getCustomerNames($term);

        $json_array = array();
        foreach ($rows as $row)
            array_push($json_array, $row->name);

        echo json_encode($json_array);
    }

}
