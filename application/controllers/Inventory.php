<?php

class Inventory extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper('form');
        if (!$this->session->has_userdata('isloggedin')) {
            redirect('/login');
        }
    }

    public function index() {
        redirect('inventory/page');
    }

    public function page() {
        $this->load->library('pagination');
        $perpage = 20;
        $config['base_url'] = base_url() . "inventory/page";
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = ' </ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        if ($this->session->userdata('type') == 0 OR $this->session->userdata('type') == 1) {
            $config['total_rows'] = $this->item_model->getCount('product', array("status" => 1));
            $this->pagination->initialize($config);
            $products = $this->item_model->getItemsWithLimit('product', $perpage, $this->uri->segment(3), 'product_name', 'ASC', array("status" => 1));
            $data = array(
                'title' => 'Inventory Management',
                'heading' => 'Inventory',
                'products' => $products,
                'links' => $this->pagination->create_links()
            );

            $this->load->view("paper/includes/header", $data);
            $this->load->view("paper/inventory/inventory");
            $this->load->view("paper/includes/footer");
        } else {
            redirect("home/");
        }
    }

    public function view() {
        if ($this->session->userdata('type') == 0 OR $this->session->userdata('type') == 1) {
            $product = $this->item_model->fetch('product', array('product_id' => $this->uri->segment(3)));
            $data = array(
                'title' => "Inventory: View Product",
                'heading' => "Inventory",
                'products' => $product
            );
            $this->load->view('paper/includes/header', $data);
            $this->load->view('paper/inventory/view');
            $this->load->view('paper/includes/footer');
        } else {
            redirect('home');
        }
    }

    public function add_product() {
        if (($this->session->userdata('type') == 0) OR ( $this->session->userdata('type') == 1)) {
            $data = array(
                'title' => 'Inventory: Add Product',
                'heading' => 'Inventory'
            );

            $this->load->view('paper/includes/header', $data);
            $this->load->view('paper/inventory/add_product');
            $this->load->view('paper/includes/footer');
        } else {
            redirect('home');
        }
    }

    public function add_product_exec() {
        $this->form_validation->set_rules('supplier', "Please put the supplier company.", "required");
        $this->form_validation->set_rules('product_name', "Please put the product name.", "required");
        $this->form_validation->set_rules('product_price', "Please put the product price.", "required|numeric");
        $this->form_validation->set_rules('product_quantity', "Please put the product quantity.", "required|numeric");
        $this->form_validation->set_rules('product_desc', "Please put a description for the product.", "required");
        $this->form_validation->set_message('required', '{field}');

        if ($this->form_validation->run()) {
            $this->load->library('upload');
            $dataInfo = array();
            $files = $_FILES;
            $cpt = count($_FILES['user_file']['name']);
            for ($i = 0; $i < $cpt; $i++) {
                $_FILES['user_file']['name'] = $files['user_file']['name'][$i];
                $_FILES['user_file']['type'] = $files['user_file']['type'][$i];
                $_FILES['user_file']['tmp_name'] = $files['user_file']['tmp_name'][$i];
                $_FILES['user_file']['error'] = $files['user_file']['error'][$i];
                $_FILES['user_file']['size'] = $files['user_file']['size'][$i];

                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload('user_file');
                $dataInfo[] = $this->upload->data();
            }

            $data = array(
                'product_name' => trim($this->input->post('product_name')),
                'product_price' => $this->input->post('product_price'),
                'product_quantity' => $this->input->post('product_quantity'),
                'product_category' => $this->input->post('product_category'),
                'product_image1' => $dataInfo[0]['file_name'],
                'product_image2' => $dataInfo[1]['file_name'],
                'product_image3' => $dataInfo[2]['file_name'],
                'product_image4' => $dataInfo[3]['file_name'],
                'supplier' => trim($this->input->post('supplier')),
                'added_at' => time(),
                'product_desc' => $this->input->post('product_desc'),
                'status' => '1'
            );

            $for_log = array(
                "user_id" => $this->session->uid,
                "user_type" => $this->session->userdata('type'),
                "username" => $this->session->userdata('username'),
                "date" => time(),
                "action" => 'Added product: ' . trim($this->input->post('product_name')),
                'status' => '1'
            );
            $this->item_model->insertData('product', $data);
            $this->item_model->insertData('user_log', $for_log);
        } else {
            $this->add_product();
        }
    }

    public function edit_product() {
        if ($this->session->userdata('type') == 0 OR $this->session->userdata('type') == 1) {
            $product = $this->item_model->fetch('product', array('product_id' => $this->uri->segment(3)));
            $data = array(
                'title' => "Inventory: Edit Product",
                'heading' => "Inventory",
                'products' => $product
            );
            $this->load->view('paper/includes/header', $data);
            $this->load->view('paper/inventory/edit');
            $this->load->view('paper/includes/footer');
        } else {
            redirect("home/");
        }
    }

    public function edit_product_exec() {
        $this->form_validation->set_rules('supplier', "Please put the supplier company.", "required");
        $this->form_validation->set_rules('product_name', "Please put the product name.", "required");
        $this->form_validation->set_rules('product_price', "Please put the product price.", "required|numeric");
        $this->form_validation->set_rules('product_quantity', "Please put the product quantity.", "required|numeric");
        $this->form_validation->set_rules('product_desc', "Please put a description for the product.", "required");
        $this->form_validation->set_message('required', '{field}');

        if ($this->form_validation->run()) {
            $config['encrypt_name'] = TRUE;
            $config['upload_path'] = './uploads_products/';
            $config['allowed_types'] = "gif|jpg|png";
            $config['max_size'] = 0;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('user_file') == TRUE) {
                $image = $this->upload->data('file_name');
                $config2['image_library'] = 'gd2';
                $config2['source_image'] = './uploads_products/' . $image;
                $config2['create_thumb'] = TRUE;
                $config2['maintain_ratio'] = TRUE;
                $config2['width'] = 75;
                $config2['height'] = 50;
                $this->load->library('image_lib', $config2);
                $this->image_lib->resize();
                $data = array(
                    'product_name' => trim($this->input->post('product_name')),
                    'product_price' => $this->input->post('product_price'),
                    'product_quantity' => $this->input->post('product_quantity'),
                    'product_category' => $this->input->post('product_category'),
                    'user_file' => $image,
                    'supplier' => trim($this->input->post('supplier')),
                    'updated_at' => time(),
                    'product_desc' => $this->input->post('product_desc'),
                    'status' => '1'
                );
            } else {
                $data = array(
                    'product_name' => trim($this->input->post('product_name')),
                    'product_price' => $this->input->post('product_price'),
                    'product_quantity' => $this->input->post('product_quantity'),
                    'product_category' => $this->input->post('product_category'),
                    'supplier' => trim($this->input->post('supplier')),
                    'updated_at' => time(),
                    'product_desc' => $this->input->post('product_desc'),
                    'status' => '1'
                );
            }

            $for_log = array(
                "user_id" => $this->session->uid,
                "user_type" => $this->session->userdata('type'),
                "username" => $this->session->userdata('username'),
                "date" => time(),
                "action" => 'Deleted product #' . $this->uri->segment(3),
                'status' => '1'
            );
            $this->item_model->insertData('user_log', $for_log);
            redirect("inventory/page");
        }
    }

    public function recover_product() {
        $this->load->library('pagination');
        $perpage = 20;
        $config['base_url'] = base_url() . "inventory/recover_product";
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = ' </ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        if ($this->session->userdata('type') == 0 OR $this->session->userdata('type') == 1) {
            $config['total_rows'] = $this->item_model->getCount('product', array("status" => 0));
            $this->pagination->initialize($config);
            $products = $this->item_model->getItemsWithLimit('product', $perpage, $this->uri->segment(3), 'product_name', 'ASC', array("status" => 0));
            $data = array(
                'title' => 'Inventory: Recover Items',
                'heading' => 'Inventory',
                'products' => $products,
                'links' => $this->pagination->create_links()
            );

            $this->load->view("paper/includes/header", $data);
            $this->load->view("paper/inventory/recover");
            $this->load->view("paper/includes/footer");
        }
    }

    public function recover_product_exec() {
        $this->item_model->updatedata("product", array("status" => 1), array('product_id' => $this->uri->segment(3)));
        $for_log = array(
            "user_id" => $this->session->uid,
            "user_type" => $this->session->userdata('type'),
            "username" => $this->session->userdata('username'),
            "date" => time(),
            "action" => 'Restored product #' . $this->uri->segment(3),
            'status' => '1'
        );
        $this->item_model->insertData('user_log', $for_log);
        redirect("inventory/recover_product");
    }

    private function set_upload_options() {
        //upload an image options
        $config = array();
        $config['upload_path'] = './uploads_products/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '0';
        $config['overwrite'] = FALSE;

        return $config;
    }

}
