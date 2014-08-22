<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logging extends MX_Controller {

    function __construct() {
        parent::__construct();

// check if user logged in 
        if (!$this->ion_auth->logged_in()) {
            redirect('module=auth&view=login');
        }
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('logging_model');
    }

    function index() {

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $meta['page_title'] = $this->lang->line("logging");
        $data['page_title'] = $this->lang->line("logging");
        $this->load->view('commons/header', $meta);
        $this->load->view('list', $data);
        $this->load->view('commons/footer');
    }

    function list_logs() {
        $groups = array('editor', 'viewer', 'configuration');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $meta['page_title'] = $this->lang->line("logs");
        $data['page_title'] = $this->lang->line("logs");
        $this->load->view('commons/header', $meta);
        $this->load->view('list', $data);
        $this->load->view('commons/footer');
    }

    function clear() {
        //var_dump($data);
        $groups = array('project', 'configuration');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=branches', 'refresh');
        }
        if ($this->ion_auth->in_group(array('owner', 'admin'))) {
            $this->form_validation->set_rules('logdata', $this->lang->line("branch_name"), 'required');
            $this->form_validation->set_rules('logusr', $this->lang->line("customer"), 'required');
            $this->form_validation->set_rules('logmsg', $this->lang->line("technology"), 'required');
        }

        if ($this->form_validation->run() == true) {
            
        }
        $logData = '';
        if ($this->input->post('logdata')) {
            $logData .="1";
        } else {
            $logData .="0";
        }
        if ($this->input->post('logusr')) {
            $logData .="1";
        } else {
            $logData .="0";
        }
        if ($this->input->post('logmsg')) {
            $logData .="1";
        } else {
            $logData .="0";
        }

        if ($this->logging_model->clearLogs($logData)) {
            $this->session->set_flashdata('success_message', $this->lang->line('logs_clrd'));
            redirect("module=logging&view=list_logs", 'refresh');
        } else {

            $this->session->set_flashdata('success_message', $this->lang->line('logs_clrd'));
            $meta['page_title'] = $this->lang->line("logs");
            $data['page_title'] = $this->lang->line("logs");
            $this->load->view('commons/header', $meta);
            $this->load->view('clear', $data);
            $this->load->view('commons/footer');
        }
        //var_dump($this->input->post('logdata'));
    }

    function clear_logs() {
        $groups = array('project', 'configuration');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("logs");
        $data['page_title'] = $this->lang->line("logs");
        $this->load->view('commons/header', $meta);
        $this->load->view('clear', $data);
        $this->load->view('commons/footer');
    }

    function getLogs() {
        $this->load->library('datatables');
        $this->datatables
                ->select("lg.access_time as logtm,u1.username as usrnm,actions.name as axname,data_types.name as dtname,IF(lg.record_type=4,u2.username,IF(lg.record_type=2,c1.company,IF(lg.record_type=1,b1.branch_name,IF(lg.record_type=3,u3.username,'nodata')))) as dest", FALSE)
                ->from('logs as lg')
                ->join('users as u1', 'u1.id=lg.user_id', 'left')
                ->join('messages as m1', 'm1.id=lg.record_id', 'left')
                ->join('users as u2', 'u2.id=m1.to', 'left')
                ->join('branches as b1', 'b1.id=lg.record_id', 'left')
                ->join('users as u3', 'lg.record_id=u3.id', 'left')
                ->join('customers as c1', 'c1.id=lg.record_id', 'left')
                ->join('data_types', 'lg.record_type=data_types.id', 'left')
                ->join('actions', 'lg.altered=actions.id', 'left')
                ->group_by("lg.id");
        $this->datatables->add_column("Actions", "<center>"
                . "<a href='index.php?module=branches&amp;view=delete&amp;cid=$1&amp;brid=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_product') . "')\" class='tip' title='" . $this->lang->line("delete_log") . "'><i class='icon-trash'></i></a>"
                . "</center>");
        echo $this->datatables->generate();
    }

    function overview() {
        $groups = array('project', 'configuration');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        $chData = $this->logging_model->getChartData();
        $data['monthly_logs'] = $chData;
        $totalSent = 0;
        $totalAdded = 0;
        $totalDeleted = 0;
        $totalUpdated = 0;
        foreach ($chData as $value) {
            $totalSent += (int) $value->sent;
            $totalAdded += (int) $value->added;
            $totalDeleted += (int) $value->deleted;
            $totalUpdated += (int) $value->modified;
            //var_dump($value);
        }
        $data['tsent'] = $totalSent;
        $data['tadded'] = $totalAdded;
        $data['tdeleted'] = $totalDeleted;
        $data['tupdated'] = $totalUpdated;
        //var_dump($data);
        //return;
        //$data['stock'] = $this->logging_model->getStockValue();
        $meta['page_title'] = $this->lang->line("log_chart");
        $data['page_title'] = $this->lang->line("log_chart");
        $this->load->view('commons/header', $meta);
        $this->load->view('chart', $data);
        $this->load->view('commons/footer');
    }

    function warehouse_stock() {
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = DEFAULT_WAREHOUSE;
        }

        $data['stock'] = $this->logging_model->getWarehouseStockValue($warehouse);
        $data['warehouses'] = $this->logging_model->getAllWarehouses();
        $data['warehouse_id'] = $warehouse;
        $meta['page_title'] = $this->lang->line("warehouse_stock_value");
        $data['page_title'] = $this->lang->line("stock_value");
        $this->load->view('commons/header', $meta);
        $this->load->view('stock', $data);
        $this->load->view('commons/footer');
    }

    function sales() {
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['users'] = $this->logging_model->getAllUsers();
        $data['warehouses'] = $this->logging_model->getAllWarehouses();
        $data['customers'] = $this->logging_model->getAllCustomers();
        $data['billers'] = $this->logging_model->getAllBillers();

        $meta['page_title'] = $this->lang->line("sale_logging");
        $data['page_title'] = $this->lang->line("sale_logging");
        $this->load->view('commons/header', $meta);
        $this->load->view('sales', $data);
        $this->load->view('commons/footer');
    }

    function getSales() {
//if($this->input->get('name')){ $name = $this->input->get('name'); } else { $name = NULL; }
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
        if ($start_date) {
            if (JS_DATE == 'dd-mm-yyyy' || JS_DATE == 'dd/mm/yyyy') {
                $inv_date = trim($this->input->post('date'));
                $start_date = substr($start_date, -4) . "-" . substr($start_date, 3, 2) . "-" . substr($start_date, 0, 2);
                $end_date = substr($end_date, -4) . "-" . substr($end_date, 3, 2) . "-" . substr($end_date, 0, 2);
            } else {
                $start_date = substr($start_date, -4) . "-" . substr($start_date, 0, 2) . "-" . substr($start_date, 3, 2);
                $end_date = substr($end_date, -4) . "-" . substr($end_date, 0, 2) . "-" . substr($end_date, 3, 2);
            }
        }
        $this->load->library('datatables');
        $this->datatables
                ->select("sales.id as sid,date, reference_no, biller_name, customer_name, total_tax, total_tax2, total")
                ->from('sales')
//->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
                ->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
                ->group_by('sales.id');


        if ($user) {
            $this->datatables->like('sales.user', $user);
        }
//if($name) { $this->datatables->like('sale_items.product_name', $name, 'both'); }
        if ($biller) {
            $this->datatables->like('sales.biller_id', $biller);
        }
        if ($customer) {
            $this->datatables->like('sales.customer_id', $customer);
        }
        if ($warehouse) {
            $this->datatables->like('sales.warehouse_id', $warehouse);
        }
        if ($reference_no) {
            $this->datatables->like('sales.reference_no', $reference_no, 'both');
        }
        if ($start_date) {
            $this->datatables->where('sales.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->datatables->add_column("Actions", "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=sales&view=view_invoice&id=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\" title='" . $this->lang->line("view_invoice") . "' class='tip'><i class='icon-fullscreen'></i></a> 
			<a href='index.php?module=sales&view=pdf&id=$1' title='" . $this->lang->line("download_pdf") . "' class='tip'><i class='icon-file'></i></a> 
			<a href='index.php?module=sales&view=email_invoice&id=$1' title='" . $this->lang->line("email_invoice") . "' class='tip'><i class='icon-envelope'></i></a>
			<a href='index.php?module=sales&amp;view=edit&amp;id=$1' title='" . $this->lang->line("edit_invoice") . "' class='tip'><i class='icon-edit'></i></a>
			<a href='index.php?module=sales&amp;view=delete&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_invoice') . "')\" title='" . $this->lang->line("delete_invoice") . "' class='tip'><i class='icon-trash'></i></a></center>", "sid")
                ->unset_column('sid');


        echo $this->datatables->generate();
    }

    function purchases() {
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['users'] = $this->logging_model->getAllUsers();
        $data['warehouses'] = $this->logging_model->getAllWarehouses();
        $data['suppliers'] = $this->logging_model->getAllSuppliers();

        $meta['page_title'] = $this->lang->line("purchase_logging");
        $data['page_title'] = $this->lang->line("purchase_logging");
        $this->load->view('commons/header', $meta);
        $this->load->view('purchases', $data);
        $this->load->view('commons/footer');
    }

    function getPurchases() {
//if($this->input->get('name')){ $name = $this->input->get('name'); } else { $name = NULL; }
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
        if ($start_date) {
            if (JS_DATE == 'dd-mm-yyyy' || JS_DATE == 'dd/mm/yyyy') {
                $inv_date = trim($this->input->post('date'));
                $start_date = substr($start_date, -4) . "-" . substr($start_date, 3, 2) . "-" . substr($start_date, 0, 2);
                $end_date = substr($end_date, -4) . "-" . substr($end_date, 3, 2) . "-" . substr($end_date, 0, 2);
            } else {
                $start_date = substr($start_date, -4) . "-" . substr($start_date, 0, 2) . "-" . substr($start_date, 3, 2);
                $end_date = substr($end_date, -4) . "-" . substr($end_date, 0, 2) . "-" . substr($end_date, 3, 2);
            }
        }
        $this->load->library('datatables');
        $this->datatables
                ->select("purchases.id as id, date, reference_no, warehouses.name as wname, supplier_name, total")
                ->from('purchases')
//->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
                ->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
                ->group_by('purchases.id');

        if ($user) {
            $this->datatables->like('purchases.user', $user);
        }
//if($name) { $this->datatables->like('purchase_items.product_name', $name); }
        if ($supplier) {
            $this->datatables->like('purchases.supplier_id', $supplier);
        }
        if ($warehouse) {
            $this->datatables->like('purchases.warehouse_id', $warehouse);
        }
        if ($reference_no) {
            $this->datatables->like('purchases.reference_no', $reference_no, 'both');
        }
        if ($start_date) {
            $this->datatables->where('purchases.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->datatables->add_column("Actions", "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=inventories&view=view_inventory&id=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\" title='" . $this->lang->line("view_inventory") . "' class='tip'><i class='icon-fullscreen'></i></a> <a href='index.php?module=inventories&view=pdf&id=$1' title='" . $this->lang->line("download_pdf") . "' class='tip'><i class='icon-file'></i></a> <a href='index.php?module=inventories&view=email_inventory&id=$1' title='" . $this->lang->line("email_inventory") . "' class='tip'><i class='icon-envelope'></i></a> <a href='index.php?module=inventories&amp;view=edit&amp;id=$1' title='" . $this->lang->line("edit_inventory") . "' class='tip'><i class='icon-edit'></i></a> <a href='index.php?module=inventories&amp;view=delete&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_inventory') . "')\" title='" . $this->lang->line("delete_inventory") . "' class='tip'><i class='icon-trash'></i></a></center>", "id")
                ->unset_column('id');

        echo $this->datatables->generate();
    }

    function daily_sales() {
        if ($this->input->get('year')) {
            $year = $this->input->get('year');
        } else {
            $year = date('Y');
        }
        if ($this->input->get('month')) {
            $month = $this->input->get('month');
        } else {
            $month = date('m');
        }

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $config['translated_day_names'] = array($this->lang->line("sunday"), $this->lang->line("monday"), $this->lang->line("tuesday"), $this->lang->line("wednesday"), $this->lang->line("thursday"), $this->lang->line("friday"), $this->lang->line("saturday"));
        $config['translated_month_names'] = array('01' => $this->lang->line("january"), '02' => $this->lang->line("february"), '03' => $this->lang->line("march"), '04' => $this->lang->line("april"), '05' => $this->lang->line("may"), '06' => $this->lang->line("june"), '07' => $this->lang->line("july"), '08' => $this->lang->line("august"), '09' => $this->lang->line("september"), '10' => $this->lang->line("october"), '11' => $this->lang->line("november"), '12' => $this->lang->line("december"));

        $config['template'] = '

   			{table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered">{/table_open}
			
			{heading_row_start}<tr>{/heading_row_start}
			
			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th colspan="{colspan}" id="month_year">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
			
			{heading_row_end}</tr>{/heading_row_end}
			
			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td class="cl_wday">{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}
			
			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="day">{/cal_cell_start}
			
			{cal_cell_content}
				<div class="day_num">{day}</div>
				<div class="content">{content}</div>
			{/cal_cell_content}
			{cal_cell_content_today}
				<div class="day_num highlight">{day}</div>
				<div class="content">{content}</div>
			{/cal_cell_content_today}
			
			{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
			
			{cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}
			
			{table_close}</table>{/table_close}
';


        $this->load->library('daily_cal', $config);

        $sales = $this->logging_model->getDailySales($year, $month);

        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (!empty($sales)) {
            foreach ($sales as $sale) {
                $daily_sale[$sale->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tr><td>" . $this->lang->line("discount") . "</td><td>" . $this->ion_auth->formatMoney($sale->discount) . "</td></tr><tr><td>" . $this->lang->line("tax1") . "</td><td>" . $this->ion_auth->formatMoney($sale->tax1) . "</td></tr><tr><td>" . $this->lang->line("tax2") . "</td><td>" . $this->ion_auth->formatMoney($sale->tax2) . "</td></tr><tr><td>" . $this->lang->line("total") . "</td><td>" . $this->ion_auth->formatMoney($sale->total) . "</td></tr></table>";
            }

            /* for ($i = 1; $i <= $num; $i++){

              if(isset($cal_data[$i])) {
              $daily_sale[$i] = $cal_data[$i];
              } else {
              $daily_sale[$i] = $this->lang->line('no_sale');
              }

              }


              } else {
              for($i=1; $i<=$num; $i++) {
              $daily_sale[$i] = $this->lang->line('no_sale');
              } */
        } else {
            $daily_sale = array();
        }

        $data['calender'] = $this->daily_cal->generate($year, $month, $daily_sale);


        $meta['page_title'] = $this->lang->line("daily_sales");
        $data['page_title'] = $this->lang->line("daily_sales");
        $this->load->view('commons/header', $meta);
        $this->load->view('daily', $data);
        $this->load->view('commons/footer');
    }

    function monthly_sales() {
        if ($this->input->get('year')) {
            $year = $this->input->get('year');
        } else {
            $year = date('Y');
        }

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $data['year'] = $year;

        $data['sales'] = $this->logging_model->getMonthlySales($year);

        $meta['page_title'] = $this->lang->line("monthly_sales");
        $data['page_title'] = $this->lang->line("monthly_sales");
        $this->load->view('commons/header', $meta);
        $this->load->view('monthly', $data);
        $this->load->view('commons/footer');
    }

}
