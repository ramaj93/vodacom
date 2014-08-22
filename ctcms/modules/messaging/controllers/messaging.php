<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messaging extends MY_Controller {

    function __construct() {
        parent::__construct();


        // check if user logged in 
        if (!$this->ion_auth->logged_in()) {
            if (!$this->input->get('async') == 'true') {
            redirect('auth/login');
            }
            else return;
        }

        if ($this->ion_auth->in_group('viewer')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if (!ENABLE_MSG) {
            if (!$this->input->get('async') == 'true') {
                $this->session->set_flashdata('message', $this->lang->line("access_denied"));
                $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
                redirect('module=home', 'refresh');
            }
        }
        $this->load->library('form_validation');
        $this->load->model('messaging_model');
        //var_dump($expression);
    }

    function index() {

        if ($this->input->get('inbox')) {
            $inbox = $this->input->get('inbox');
        } else {
            $inbox = 0;
        }
        //var_dump($inbox);
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');
        $data['id'] = $this->session->userdata['user_id'];
        $meta['page_title'] = $this->lang->line("messaging");
        $data['page_title'] = $this->lang->line("messaging");
        $data['inbox'] = $inbox;

        //$this->get($inbox);
        $this->load->view('commons/header', $meta);
        $this->load->view('content', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax() {
        $this->load->library('datatables');
        $this->datatables
                ->select("IF(u1.active=1,'active','inactive') as stat,u1.id as id,u1.username,user_department.name, u1.phone, u1.email",FALSE)
                ->join("users_groups", "users_groups.user_id=u1.id")
                ->join("user_department", "users_groups.department_id=user_department.id")
                ->from("users as u1")
                ->add_column("Actions", "<center><a href='index.php?module=messaging&amp;view=send&amp;id=$1' class='tip' title='$1'><img src='" . $this->config->base_url() . "assets/img/$1.png'></i></a> <a href='index.php?module=messaging&amp;view=send&amp;id=$2' title='" . $this->lang->line("send_message") . "' onClick=\"return checkID($2)\" class='tip'><i class='icon-envelope'></i></a></center>", "stat,id")
                //->unset_column('astate')
                ->unset_column('stat')
                ->unset_column('id');


        echo $this->datatables->generate();
    }

    function check_msg() {

        if ($this->input->get('useractive') == 'true') {
            if ($this->session->userdata['active'] == 0) {
                $this->messaging_model->activateUser();
            }
        } else {
            if ($this->session->userdata['active'] == 1) {
                $this->messaging_model->inactivateUser();
            }
        }
        echo $this->messaging_model->getUnreadMsg();
    }

    function getMessages($inbox = NULL, $unread = NULL) {
        if ($inbox == NULL) {
            $inbox = $this->input->get('inbox');
        }
        if ($unread == NULL) {
            $unread = $this->input->get('unread');
        } else {
            $unread = 0;
        }


        $uid = $this->session->userdata['user_id'];
        $this->load->library('datatables');
        $this->datatables
                ->select("u1.id as id,m1.id as mid,m1.time as msgtm,u1.username as srcnm,m1.message as msg,m1.read as rdstat")
                ->from('messages as m1');
        if ($unread == 1) {
            $this->datatables
                    ->where('m1.read', 0);
        }

        $this->datatables->group_by("m1.id");
        if ($inbox == 1) {
            $this->datatables->where('m1.to', $uid)
                ->join('users as u1', 'u1.id=m1.from', 'left');
        } else {
            $this->datatables->where('m1.from', $uid)
                ->join('users as u1', 'u1.id=m1.to', 'left');
        }

        $this->datatables->add_column("Actions", "<center>
			<a href='index.php?module=messaging&view=read&amp;mid=$2' class='tip' title=\"Read Message\"><i class='icon-fullscreen'></i></a>
			<a href='index.php?module=messaging&amp;view=send&amp;id=$1' class='tip' title='" . $this->lang->line("reply") . "'><i class='icon-edit'></i></a>
			<a href='index.php?module=messaging&amp;view=delete&amp;mid=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_product') . "')\" class='tip' title='" . $this->lang->line("delete_msg") . "'><i class='icon-trash'></i></a></center>", "id,mid,rdstat");

        $this->datatables->unset_column('rdstat');
        $this->datatables->unset_column('id');
        $this->datatables->unset_column('mid');
        echo $this->datatables->generate();
        $this->messaging_model->clearUnread();
    }


    function read($mid) {
        if ($mid == null) {
            $mid = $this->input->get('mid');
        }

        $msg = $this->messaging_model->getMessage(NULL, $mid);
        $data['msg'] = $msg->message;
        //var_dump($msg);
        $meta['page_title'] = $this->lang->line("send_msg");
        $data['id'] = $msg->from;
        $data['page_title'] = $this->lang->line("msg_from") . $this->messaging_model->getUserByID($msg->from)->username;
        $this->load->view('commons/header', $meta);
        $this->load->view('read', $data);
        $this->load->view('commons/footer');
    }

    function clear()
    {
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
            $this->form_validation->set_rules('clrmsg', $this->lang->line("clrmsg"), 'required|xss_clean');
        } 
        if ($this->form_validation->run() == true) {
            $data['interval'] = $this->input->post('clrmsg');

        }

        if ($this->form_validation->run() == true && $this->messaging_model->clearMessages($data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("msg_clrd"));
            redirect("module=messaging", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $data['page_title'] = $this->lang->line("clr_msg");
            $data['intervals'] = array('1 Month old','2 Months Old','3 Months Old','4 Months Old','5 Months Old','All');
            $this->load->view('commons/header', $meta);
            $this->load->view('clear', $data);
            $this->load->view('commons/footer');
        }     
    }
    function get($inbox, $unread = NULL) {
        //validate form input
        $inbox = $this->input->get('inbox');
        $unread = $this->input->get('unread');
        $data['inbox'] = $inbox;
        $data['unread'] = $unread;
        //var_dump($data);
        if ($inbox == 1) {
            $meta['page_title'] = $this->lang->line("inbox");
            $data['page_title'] = $this->lang->line("inbox");
        } else {
            $meta['page_title'] = $this->lang->line("outbox");
            $data['page_title'] = $this->lang->line("outbox");
        }
        $this->load->view('commons/header', $meta);
        $this->load->view('get', $data);
        $this->load->view('commons/footer');
    }

    function send($id = NULL) {
        //var_dump(ENABLE_LOGGING);
        //return;
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $uid = $this->session->userdata['user_id'];

        //validate form input
        $this->form_validation->set_rules('message', $this->lang->line("message"), 'required|xss_clean');


        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'from' => $uid,
                'to' => $id,
                'message' => $this->input->post('message'),
            );
        }

        if ($this->form_validation->run() && $this->messaging_model->sendMessage($data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("msg_sent"));
            redirect("module=messaging", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $meta['page_title'] = $this->lang->line("send_msg");
            $data['id'] = $id;
            $data['page_title'] = $this->lang->line("send_msg") . " to " . $this->messaging_model->getUserByID($id)->username;
            $this->load->view('commons/header', $meta);
            $this->load->view('send', $data);
            $this->load->view('commons/footer');
        }
    }

    function delete($mid = NULL) {

        if ($this->input->get('mid')) {
            $id = $this->input->get('mid');
        }
        if ($this->ion_auth->in_group('viewer')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if ($this->messaging_model->deleteMessage($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("msg_deleted"));
            redirect("module=messaging", 'refresh');
        }
    }

}

/* End of file billers.php */ 
/* Location: ./sma/modules/billers/controllers/billers.php */