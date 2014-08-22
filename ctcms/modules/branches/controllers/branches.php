<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branches extends MY_Controller {
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
      | MODULE: 			branches
      | -----------------------------------------------------
      | This is branches module controller file.
      | -----------------------------------------------------
     */

    function __construct() {
        parent::__construct();

        // check if user logged in 
        if (!$this->ion_auth->logged_in()) {
            redirect('module=auth&view=login');
        }

        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('branches_model');
    }

    function index() {
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $ownerID = 1;
        $id = $this->session->userdata['user_id'];

        $userGroup = $this->branches_model->getUserGroup($id);


        $data['user_group'] = $userGroup->group_id;
        $whid = '';
        $data['is_owner'] = TRUE;
        $data['warehouses'] = $this->branches_model->getAllCustomers();

        //var_dump($data['user_group']);

        $meta['page_title'] = $this->lang->line("branches");
        $data['page_title'] = $this->lang->line("branches");

        $this->load->view('commons/header', $meta);
        $this->load->view('index', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajaxadmin() {
        $this->load->library('datatables');
        $this->datatables
                ->select("branches.customer_id as cst_id,branches.id as br_id,customers.company as cname,branches.branch_name as name,technologies.name as technology,"
                        . "services.name as service,branches.mac_addr as mac,bandwidth.name as bmane,branches.donor_site as donor_site,branches.region as region")
                ->from('branches')
                ->join('customers', 'branches.customer_id=customers.id', 'left')
                ->join('services', 'branches.service_id=services.id', 'left')
                ->join('technologies', 'branches.technology_id=technologies.id', 'left')
                ->join('bandwidth', 'branches.bandwidth_id=bandwidth.id', 'left')
                ->group_by("branches.id");
        $this->datatables->add_column("Actions", "<center>
			<a href='#' onClick=\"MyWindow=window.open('index.php?module=branches&view=branch_details&amp;cid=$1&amp;brid=$2', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("branch_details") . "'><i class='icon-fullscreen'></i></a>
			<a href='index.php?module=branches&amp;view=edit&amp;cid=$1&amp;brid=$2' class='tip' title='" . $this->lang->line("edit_branch") . "'><i class='icon-edit'></i></a>
			<a href='index.php?module=branches&amp;view=delete&amp;cid=$1&amp;brid=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_product') . "')\" class='tip' title='" . $this->lang->line("delete_branch") . "'><i class='icon-trash'></i></a></center>", "cst_id, br_id, code, name");

        $this->datatables->unset_column('cst_id');
        $this->datatables->unset_column('br_id');
        //var_dump($this->datatables->generate());
        //return;
        echo $this->datatables->generate();
    }

    function getdatatableajax() {
        $this->load->library('datatables');
        $this->datatables
                ->select("branches.customer_id as cst_id,branches.id as br_id,customers.company as cname,branches.branch_name as name,technologies.name as technology,"
                        . "services.name as service,branches.mac_addr as mac,bandwidth.name as bname,branches.donor_site as donor_site,branches.region as region")
                ->from('branches')
                ->join('customers', 'branches.customer_id=customers.id', 'left')
                ->join('services', 'branches.service_id=services.id', 'left')
                ->join('technologies', 'branches.technology_id=technologies.id', 'left')
                ->join('bandwidth', 'branches.bandwidth_id=bandwidth.id', 'left')
                ->group_by("branches.id");
        $this->datatables->add_column("Actions", "<center>"
                . "<a href='#' onClick=\"MyWindow=window.open('index.php?module=branches&view=branch_details&amp;cid=$1&amp;brid=$2', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("branch_details") . "'><i class='icon-fullscreen'></i></a>"
                . "<a href='index.php?module=branches&amp;view=edit&amp;cid=$1&amp;brid=$2' class='tip' title='" . $this->lang->line("edit_branch") . "'><i class='icon-edit'></i></a></center>", "cst_id, br_id, code, name");

        $this->datatables->unset_column('cst_id');
        $this->datatables->unset_column('br_id');
        //var_dump($this->datatables->generate());
        //return;
        echo $this->datatables->generate();
    }

    function warehouse($warehouse = DEFAULT_WAREHOUSE) {
        if ($this->input->get('warehouse_id')) {
            $warehouse = $this->input->get('warehouse_id');
        } elseif (isset($data['warehouse_id'])) {
            $warehouse = $data['warehouse_id'];
        }

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $id = $this->session->userdata['user_id'];
        $userGroup = $this->branches_model->getUserGroup($id)->group_id;

        $data['warehouses'] = $this->branches_model->getAllCustomers();
        $data['warehouse_id'] = $warehouse;

        $meta['page_title'] = $this->lang->line("branches");
        $data['page_title'] = $this->lang->line("branches");
        $this->load->view('commons/header', $meta);
        $this->load->view('warehouse', $data);
        $this->load->view('commons/footer');
    }

    function getwhbranches($warehouse_id) {
        if ($this->input->get('warehouse_id')) {
            $warehouse_id = $this->input->get('warehouse_id');
        }
        //var_dump($this->ion_auth->in_group('owner'));
        $this->load->library('datatables');
        if ($this->ion_auth->in_group(array('owner','admin'))) {
            $this->datatables
                    ->select("branches.customer_id as cst_id,branches.id as br_id,customers.company as cname,branches.branch_name as name,technologies.name as technology,"
                            . "services.name as service,branches.mac_addr as mac,bandwidth.name as bmane,branches.donor_site as donor_site,branches.region as region")
                    ->from('branches')
                    ->join('customers', 'branches.customer_id=customers.id', 'left')
                    ->join('services', 'branches.service_id=services.id', 'left')
                    ->join('technologies', 'branches.technology_id=technologies.id', 'left')
                    ->join('bandwidth', 'branches.bandwidth_id=bandwidth.id', 'left')
                    ->where('customers.id', $warehouse_id)
                    ->group_by("branches.id");


            $this->datatables->add_column("Actions", "<center>
			<a href='#' onClick=\"MyWindow=window.open('index.php?module=branches&view=product_details&amp;cid=$1&amp;brid=$2', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("product_details") . "'><i class='icon-fullscreen'></i></a>
			<a href='index.php?module=branches&amp;view=edit&amp;cid=$1&amp;brid=$2' class='tip' title='" . $this->lang->line("edit_product") . "'><i class='icon-edit'></i></a>
			<a href='index.php?module=branches&amp;view=delete&amp;cid=$1&amp;brid=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_product') . "')\" class='tip' title='" . $this->lang->line("delete_product") . "'><i class='icon-trash'></i></a></center>", "cst_id, br_id");
        } else {
            $this->datatables
                    ->select("branches.customer_id as cst_id,branches.id as br_id,customers.company as cname,branches.branch_name as name,technologies.name as technology,"
                            . "services.name as service,branches.mac_addr as mac,bandwidth.name as bmane,branches.donor_site as donor_site,branches.region as region")
                    ->from('branches')
                    ->join('customers', 'branches.customer_id=customers.id', 'left')
                    ->join('services', 'branches.service_id=services.id', 'left')
                    ->join('technologies', 'branches.technology_id=technologies.id', 'left')
                    ->join('bandwidth', 'branches.bandwidth_id=bandwidth.id', 'left')
                    ->where('customers.id', $warehouse_id)
                    ->group_by("branches.id");
            $this->datatables->add_column("Actions", "<center>
			<a href='#' onClick=\"MyWindow=window.open('index.php?module=branches&view=branch_details&amp;cid=$1&amp;brid=$2', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=500'); return false;\" class='tip' title='" . $this->lang->line("product_details") . "'><i class='icon-fullscreen'></i></a></center>", "cst_id, br_id");
        }
        $this->datatables->unset_column('cst_id')
                ->unset_column('br_id');


        echo $this->datatables->generate();
    }


    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function add() {
        $groups = array('owner', 'admin', 'project');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=branches', 'refresh');
        }

        $data['customers'] = $this->branches_model->getAllCustomers();

        if (!$this->ion_auth->in_group('configuration')) {
            $this->form_validation->set_rules('br_name', $this->lang->line("branch_name"), 'required|xss_clean');
            $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required|xss_clean');
            $this->form_validation->set_rules('technology', $this->lang->line("technology"), 'required|xss_clean');
            $this->form_validation->set_rules('subcategory', $this->lang->line("subcategory"), 'xss_clean');
            $this->form_validation->set_rules('bandwidth', $this->lang->line("bandwidth"), 'required|xss_clean');
            $this->form_validation->set_rules('donor_site', $this->lang->line("donor_site"), 'required|xss_clean');
            $this->form_validation->set_rules('service', $this->lang->line("service"), 'required|xss_clean');
            $this->form_validation->set_rules('mac_addr', $this->lang->line("mac_addr"), 'required|xss_clean');
            $this->form_validation->set_rules('region', $this->lang->line("region"), 'required|xss_clean');
        } else {
            $this->form_validation->set_rules('serv_ip', $this->lang->line("branch_name"), 'required|xss_clean');
            $this->form_validation->set_rules('bwan_ip', $this->lang->line("customer"), 'required|xss_clean');
            $this->form_validation->set_rules('bgatwy_ip', $this->lang->line("technology"), 'required|xss_clean');
            $this->form_validation->set_rules('bsbn_mask', $this->lang->line("subcategory"), 'xss_clean');
            $this->form_validation->set_rules('bvlan_ip', $this->lang->line("bandwidth"), 'required|xss_clean');
            $this->form_validation->set_rules('man_ip', $this->lang->line("donor_site"), 'required|xss_clean');
            $this->form_validation->set_rules('mwan_ip', $this->lang->line("service"), 'required|xss_clean');
            $this->form_validation->set_rules('mgatwy_ip', $this->lang->line("mac_addr"), 'required|xss_clean');
            $this->form_validation->set_rules('msbn_mask', $this->lang->line("region"), 'required|xss_clean');
            $this->form_validation->set_rules('mvlan_ip', $this->lang->line("region"), 'required|xss_clean');
        }


        if ($this->form_validation->run() == true) {
            $data = array(
                'cst_id' => $this->input->post('customer'),
                'branch_name' => $this->input->post('br_name'),
                'technology' => $this->input->post('technology'),
                'service' => $this->input->post('service'),
                'trpr1' => $this->input->post('trpr1'),
                'trpr2' => $this->input->post('trpr2'),
                'mac_addr' => $this->input->post('mac_addr'),
                'donor_site' => $this->input->post('donor_site'),
                'region' => $this->input->post('region'),
                'serv_ip' => $this->input->post('serv_ip'),
                'bandwidth' => $this->input->post('bandwidth'),
                'bwan_ip' => $this->input->post('bwan_ip'),
                'bgatwy_ip' => $this->input->post('bgatwy_ip'),
                'bsbn_mask' => $this->input->post('bsbn_mask'),
                'bvlan_ip' => $this->input->post('bvlan_ip'),
                'man_ip' => $this->input->post('man_ip'),
                'mwan_ip' => $this->input->post('mwan_ip'),
                'mgatwy_ip' => $this->input->post('mgatwy_ip'),
                'msbn_mask' => $this->input->post('msbn_mask'),
                'mvlan_ip' => $this->input->post('mvlan_ip')
            );
        }

        if ($this->form_validation->run() == true && $this->branches_model->addBranch($data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("branch_added"));

            redirect('module=branches', 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $data['technologies'] = $this->branches_model->getAllTechnologies();
            $data['bandwidths'] = $this->branches_model->getAllBandwidths();
            $data['services'] = $this->branches_model->getAllServices();
            $meta['page_title'] = $this->lang->line("add_branch");
            $data['page_title'] = $this->lang->line("add_branch");
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');
        }
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function edit($cid, $brid) {
        if ($this->input->get('brid')) {
            $brid = $this->input->get('brid');
            $cid = $this->input->get('cid');
        }
        $groups = array('owner','project', 'configuration','admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=branches', 'refresh');
        }


        //validate form input
        if (!$this->ion_auth->in_group('configuration')) {
            $this->form_validation->set_rules('br_name', $this->lang->line("branch_name"), 'required|xss_clean');
            $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required|xss_clean');
            $this->form_validation->set_rules('technology', $this->lang->line("technology"), 'required|xss_clean');
            $this->form_validation->set_rules('subcategory', $this->lang->line("subcategory"), 'xss_clean');
            $this->form_validation->set_rules('bandwidth', $this->lang->line("bandwidth"), 'required|xss_clean');
            $this->form_validation->set_rules('donor_site', $this->lang->line("donor_site"), 'required|xss_clean');
            $this->form_validation->set_rules('service', $this->lang->line("service"), 'required|xss_clean');
            $this->form_validation->set_rules('mac_addr', $this->lang->line("mac_addr"), 'required|xss_clean');
            $this->form_validation->set_rules('region', $this->lang->line("region"), 'required|xss_clean');
        } 
        if(!$this->ion_auth->in_group('project')){
            $this->form_validation->set_rules('serv_ip', $this->lang->line("serv_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('bwan_ip', $this->lang->line("wan_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('bgatwy_ip', $this->lang->line("gateway_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('bsbn_mask', $this->lang->line("subnet_mask"), 'xss_clean');
            $this->form_validation->set_rules('bvlan_ip', $this->lang->line("vlan_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('man_ip', $this->lang->line("man_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('mgatwy_ip', $this->lang->line("gateway_ip"), 'required|xss_clean');
            $this->form_validation->set_rules('msbn_mask', $this->lang->line("subnet_mask"), 'required|xss_clean');
            $this->form_validation->set_rules('mvlan_ip', $this->lang->line("vlan_ip"), 'required|xss_clean');
        }
        if ($this->form_validation->run() == true) {
            $data = array(
                'cst_id' => $this->input->post('customer'),
                'branch_name' => $this->input->post('br_name'),
                'technology' => $this->input->post('technology'),
                'service' => $this->input->post('service'),
                'trpr1' => $this->input->post('trpr1'),
                'trpr2' => $this->input->post('trpr2'),
                'mac_addr' => $this->input->post('mac_addr'),
                'donor_site' => $this->input->post('donor_site'),
                'region' => $this->input->post('region'),
                'serv_ip' => $this->input->post('serv_ip'),
                'bandwidth' => $this->input->post('bandwidth'),
                'bwan_ip' => $this->input->post('bwan_ip'),
                'bgatwy_ip' => $this->input->post('bgatwy_ip'),
                'bsbn_mask' => $this->input->post('bsbn_mask'),
                'bvlan_ip' => $this->input->post('bvlan_ip'),
                'man_ip' => $this->input->post('man_ip'),
                'mwan_ip' => $this->input->post('mwan_ip'),
                'mgatwy_ip' => $this->input->post('mgatwy_ip'),
                'msbn_mask' => $this->input->post('msbn_mask'),
                'mvlan_ip' => $this->input->post('mvlan_ip')
            );
        }

        if ($this->form_validation->run() == true && $this->branches_model->updateBranch($cid, $brid, $data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("product_updated"));
            redirect("module=branches", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $product_details = $this->branches_model->getBranchByID($cid, $brid);

            $data['customers'] = $this->branches_model->getAllCustomers();
            $data['services'] = $this->branches_model->getAllServices();
            $meta['page_title'] = $this->lang->line("update_branch");
            $data['brid'] = $brid;
            $data['cid'] = $cid;
            $data['branch'] = $product_details;
            $data['technologies'] = $this->branches_model->getAllTechnologies();
            $data['bandwidths'] = $this->branches_model->getAllBandwidths();
            if (!$this->ion_auth->in_group('project')) {
                $data['brips'] = $this->branches_model->getBranchIPAddresses($brid);
                $data['manips'] = $this->branches_model->getBranchManagementAddresses($brid);
            }
            $data['page_title'] = $this->lang->line("update_branch");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');
        }
    }

    /* ----------------------------------------------------------------------------------------------------------------------------------------- */

    function upload_csv() {
        $groups = array('purchaser', 'salesman', 'viewer');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=branches', 'refresh');
        }

        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
                redirect('module=home', 'refresh');
            }

            $category = $this->input->post('category');
            if (isset($_FILES["userfile"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload_photo');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '200';
                $config['overwrite'] = TRUE;

                $this->upload_photo->initialize($config);

                if (!$this->upload_photo->do_upload()) {

                    $error = $this->upload_photo->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=branches&view=upload_csv", 'refresh');
                }

                $csv = $this->upload_photo->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'name', 'category_code', 'unit', 'size', 'cost', 'price', 'alert_quantity');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) {
                    if ($this->branches_model->getProductByCode($csv_pr['code'])) {
                        $this->session->set_flashdata('message', $this->lang->line("check_product_code") . " (" . $csv_pr['code'] . "). " . $this->lang->line("code_already_exist") . " " . $this->lang->line("line_no") . " " . $rw);
                        redirect("module=branches&view=upload_csv", 'refresh');
                    }
                    if ($catd = $this->branches_model->getCategoryByCode($csv_pr['category_code'])) {
                        $pr_code[] = $csv_pr['code'];
                        $pr_name[] = $csv_pr['name'];
                        $pr_cat[] = $catd->id;
                        $pr_unit[] = $csv_pr['unit'];
                        $pr_size[] = $csv_pr['size'];
                        $pr_cost[] = $csv_pr['cost'];
                        $pr_price[] = $csv_pr['price'];
                        $pr_aq[] = $csv_pr['alert_quantity'];
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("check_category_code") . " (" . $csv_pr['category_code'] . "). " . $this->lang->line("category_code_x_exist") . " " . $this->lang->line("line_no") . " " . $rw);
                        redirect("module=branches&view=upload_csv", 'refresh');
                    }

                    $rw++;
                }
            }

            $ikeys = array('code', 'name', 'category_id', 'unit', 'size', 'cost', 'price', 'alert_quantity');

            $items = array();
            foreach (array_map(null, $pr_code, $pr_name, $pr_cat, $pr_unit, $pr_size, $pr_cost, $pr_price, $pr_aq) as $ikey => $value) {
                $items[] = array_combine($ikeys, $value);
            }

            $final = $this->mres($items);
            //$data['final'] = $final;
        }

        if ($this->form_validation->run() == true && $this->branches_model->add_branches($final)) {
            $this->session->set_flashdata('success_message', $this->lang->line("branches_added"));
            redirect('module=branches', 'refresh');
        } else {

            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $data['categories'] = $this->branches_model->getAllCategories();
            $meta['page_title'] = $this->lang->line("csv_add_branches");
            $data['page_title'] = $this->lang->line("csv_add_branches");
            $this->load->view('commons/header', $meta);
            $this->load->view('upload_csv', $data);
            $this->load->view('commons/footer');
        }
    }

    /* -------------------------------------------------------------------------------------------------------------------------------------- */

    /* ---------------------------------------------------------------------------------------------------------------------------------------- */

    function delete($brid, $cid) {
        if ($this->input->get('id')) {
            $brid = $this->input->get('id');
            $cid = $this->input->get('cid');
        }
        if (DEMO) {
            $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
            redirect('module=home', 'refresh');
        }

        $groups = array('purchaser', 'salesman', 'viewer');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=branches', 'refresh');
        }

        if ($this->branches_model->deleteProduct($cid, $brid)) {
            $this->session->set_flashdata('success_message', $this->lang->line("product_deleted"));
            redirect('module=branches', 'refresh');
        }
    }

    /* ----------------------------------------------------------------------------------------------------------------------------- */

    /* ------------------------------------------------------------------------------------------------------------------------ */


    function branch_details($brid, $cid) {

        if ($this->input->get('brid')) {
            $brid = $this->input->get('brid');
            $cid = $this->input->get('cid');
        }
        //var_dump($cid);
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

        $br_details = $this->branches_model->getBranchByID($cid, $brid);

        $customer = $this->branches_model->getCustomerByID($br_details->customer_id)->company;
        $data['branch'] = $br_details;
        $data['technology'] = $this->branches_model->getTechnologyByID($br_details->technology_id)->name;
        $data['brips'] = $this->branches_model->getBranchIPAddresses($brid);
        $data['manips'] = $this->branches_model->getBranchManagementAddresses($brid);
        //var_dump($data['manips']);
        $data['customer'] = $customer;
        $data['service'] = $this->branches_model->getServiceByID($br_details->service_id);
        $meta['page_title'] = $this->lang->line("branch_details");
        $data['page_title'] = $this->lang->line("branch_details");
        $this->load->view('details', $data);
    }

  
}
