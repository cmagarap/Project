<?php

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->library(array('email', 'session', 'form_validation', 'basket'));
    }

    public function index() {
        if ($this->session->has_userdata('isloggedin')) {
            if ($this->session->userdata("type") == 2) { # if customer

                $image = $this->item_model->fetch('content')[0];

                $data = array(
                    'title' => "TECHNOHOLICS | All the tech you need.",
                    'CTI' => $this->basket->total_items(),
                    'page' => "Home", // active column identifier
                    'image' => $image
                );

                $this->load->view('ordering/includes/header', $data);
                $this->load->view('ordering/includes/navbar');
                $this->load->view('ordering/ads/front_slider');
                $this->load->view('ordering/ads/featured_products');
                $this->load->view('ordering/includes/footer');
            } elseif ($this->session->userdata("type") == 0 OR $this->session->userdata("type") == 1) {
                redirect("dashboard");
            }
        } else { # if not logged in


            $image = $this->item_model->fetch('content')[0];


            $data = array(
                'title' => "TECHNOHOLICS | All the tech you need.",
                'CTI' => $this->basket->total_items(),
                'page' => "Home",
                'image' => $image
            );

            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/ads/front_slider');
            $this->load->view('ordering/ads/featured_products');
            $this->load->view('ordering/includes/footer');
        }
    }

    public function test() {
        $bday = new DateTime('1994-9-17');

        $today = new DateTime(Date('Y-m-d'));

        $diff = $today->diff($bday);

        $age = sprintf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);

        echo $age;
    }

    public function auto() {
        $output = '';
        $query = $this->item_model->search('product', 'status = 1 AND product_name', $this->input->post('query'));
        $output = '<ul class="box list-unstyled" style="width:420px;">';

        if ($query) {
            foreach ($query as $query) {
                $output .= '<li id="link" class="text-left" style="cursor:pointer;">' . $query->product_name . '</li>';
            }
        } else {
            $output .= '<li class="text-left" >Item Not Found</li>';
        }
        $output .= '</ul>';
        echo $output;
    }

    public function category() {
        $page = $this->uri->segment(2);
        $cat = $this->uri->segment(3);
        $brand = ctype_alpha($this->uri->segment(4)) ? $this->uri->segment(4) : NULL;

        $this->load->library('pagination');
        $this->session->set_userdata('sort', $this->input->post('sort') ? $this->input->post('sort') : $this->session->userdata('sort'));
        $sort = $this->session->userdata('sort')?$this->session->userdata('sort'):"product_name";
        $this->session->set_userdata('perpage', $this->input->post('limit') ? $this->input->post('limit') : $this->session->userdata('perpage'));
        $perpage = $this->session->userdata('perpage')?$this->session->userdata('perpage'):12;
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

        if ($brand == "Apple" || $brand == "Samsung" || $brand == "ASUS" || $brand == "Lenovo" || $brand == "Sony" || $brand == "HP" || $brand == "Dell" || $brand == "Acer" || $brand == "OPPO" || $brand == "Huawei") {
            $config['base_url'] = base_url() . "home/category/" . $cat . "/" . $brand;
            $config['total_rows'] = $this->item_model->getCount('product', array("status" => 1, "product_category" => $cat, "product_brand" => $brand));
            $count = $this->item_model->getCount('product', array("status" => 1, "product_category" => $cat, "product_brand" => $brand));
            $this->pagination->initialize($config);
            $product = $this->item_model->getItemsWithLimit('product', $perpage, $this->uri->segment(5), $sort, 'ASC', array("status" => 1, "product_category" => $cat, "product_brand" => $brand));
            $data = array(
                'title' => 'Category',
                'products' => $product,
                'count' => $count,
                'page' => $page,
                'category' => $cat, // category identifier
                'brand' => $brand,
                'sort'=> $sort,
                'perpage' => $perpage,
                'CTI' => $this->basket->total_items(),
                'links' => $this->pagination->create_links()
            );
            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/category');
            $this->load->view('ordering/includes/footer');
        } else {
            $config['base_url'] = base_url() . "home/category/" . $cat;
            $config['total_rows'] = $this->item_model->getCount('product', array("status" => 1, "product_category" => $cat));
            $count = $this->item_model->getCount('product', array("status" => 1, "product_category" => $cat));
            $this->pagination->initialize($config);
            $product = $this->item_model->getItemsWithLimit('product', $perpage, $this->uri->segment(4), $sort, 'ASC', array("status" => 1, "product_category" => $cat));
            $data = array(
                'title' => 'Category',
                'products' => $product,
                'count' => $count,
                'page' => $page,
                'category' => $cat, // category identifier
                'brand' => $brand,
                'sort'=> $sort,
                'perpage' => $perpage,
                'CTI' => $this->basket->total_items(),
                'links' => $this->pagination->create_links()
            );

            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/category');
            $this->load->view('ordering/includes/footer');
        }
    }

    public function search() {
        $this->load->library('pagination');
        $this->session->set_userdata('sort', $this->input->post('sort') ? $this->input->post('sort') : $this->session->userdata('sort'));
        $sort = $this->session->userdata('sort')?$this->session->userdata('sort'):"product_name";
        $this->session->set_userdata('perpage', $this->input->post('limit') ? $this->input->post('limit') : $this->session->userdata('perpage'));
        $perpage = $this->session->userdata('perpage')?$this->session->userdata('perpage'):12;
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

        $this->session->set_userdata('search', $this->input->post('search') ? $this->input->post('search') : $this->session->userdata('search'));
        $search = $this->session->userdata('search');

        $config['base_url'] = base_url() . "home/search";
        $config['total_rows'] = $this->item_model->getCountsearch('product', 'status = 1 AND product_name', $search);
        $count = $this->item_model->getCountsearch('product', 'status = 1 AND product_name', $search);
        $this->pagination->initialize($config);
        $product = $this->item_model->getItemsWithLimitSearch('product', $perpage, $this->uri->segment(3), $sort, 'ASC', 'status = 1 AND product_name', $search);

        $for_update = $product[0];

        $this->item_model->updatedata('product', array('times_searched' => $for_update->times_searched + 1), "product_name = '$search'");

        if($this->session->has_userdata('isloggedin')) {
            foreach ($product as $product_r) {
                $for_audit = array(
                    "customer_name" => $this->session->userdata("username"),
                    "item_name" => $product_r->product_name,
                    "at_detail" => "Search",
                    "at_date" => time(),
                    "customer_id" => $this->session->uid,
                    "product_id" => $product_r->product_id
                );
                $this->item_model->insertData('audit_trail', $for_audit);
            }
        }

        $data = array(
            'title' => 'Home',
            'products' => $product,
            'page' => "category",
            'count' => $count,
            'category' => $search, // category identifier
            'brand' => "",
            'sort'=> $sort,
            'perpage' => $perpage,
            'CTI' => $this->basket->total_items(),
            'links' => $this->pagination->create_links()
        );

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/category');
        $this->load->view('ordering/includes/footer');
    }

    public function register() {
        $data = array(
            'title' => "TECHNOHOLICS | All the tech you need.",
            'CTI' => $this->basket->total_items()
        );
        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/register');
        $this->load->view('ordering/includes/footer');
    }

    public function basket() {
        $product = $this->item_model->fetch('product',NULL,NULL,NULL, 3);
        $data = array(
            'title' => "My Shopping Cart",
            'cartItems' => $this->basket->contents(),
            'product' => $product,
            'CT' => $this->basket->total(),
            'CTI' => $this->basket->total_items(),
            'page' => "Home"
        );

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/basket');
        $this->load->view('ordering/includes/footer');
    }

    public function detail() {

        $page = "category";
        $cat = $this->uri->segment(3);
        $brand = $this->uri->segment(4);
        $id = $this->uri->segment(5);

        $this->load->library('pagination');
        $perpage = 5;
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

        $config['base_url'] = base_url() . "home/detail/" . $cat . "/" . $brand . "/" . $id . "/page";
        $config['total_rows'] = $this->item_model->getCount('feedback', array('product_id' => $id));
        $this->pagination->initialize($config);
        $feedback = $this->item_model->getItemsWithLimit('feedback', $perpage, $this->uri->segment(7), 'feedback_id', 'ASC', 'product_id = ' . $id . ' AND status = 1');
        $product = $this->item_model->fetch('product', array('product_id' => $id));
        $condition = $this->item_model->fetch('wishlist',array('customer_id' => $this->session->uid, 'product_id' => $id));
        $row = $product[0];

        $data = array(
            'title' => $row->product_name,
            'product' => $product,
            'feedback' => $feedback,
            'page' => $page,
            'category' => $cat, //category identifier
            'brand' => $brand,
            'row' => $row,
            'condition' => $condition,
            'CTI' => $this->basket->total_items(),
            'links' => $this->pagination->create_links()
        );

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/detail');
        $this->load->view('ordering/includes/footer');
    }

    public function checkout1() {
        if ($this->session->has_userdata('isloggedin')) {
            $this->checkout2();
        } else {
            $data = array(
                'title' => "Checkout - Address",
                'CTI' => $this->basket->total_items(),
                'page' => "Home"
            );
            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/checkout1');
            $this->load->view('ordering/includes/footer');
        }
    }

    public function checkout1_exec(){

        $shipper = $this->item_model->fetch('shipper');

        if ($this->session->has_userdata('isloggedin')) {
            $data = array(
                'title' => "Checkout - Delivery method",
                'page' => "Home",
                'shipper' => $shipper,
                'CTI' => $this->basket->total_items()
            );

            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/checkout2');
            $this->load->view('ordering/includes/footer');
        }

        else{

            $this->form_validation->set_rules('email', "Please put your email.", "required");
            $this->form_validation->set_rules('firstname', "Please put your first name.", "required");
            $this->form_validation->set_rules('lastname', "Please put your lastname name.", "required");
            $this->form_validation->set_rules('address', "Please put your complete address.", "required");
            $this->form_validation->set_rules('province', "Please put the name of your province.", "required");
            $this->form_validation->set_rules('city', "Please put the name of your City / Municipality.", "required");
            $this->form_validation->set_rules('barangay', "Please put the name of your barangay.", "required");
            $this->form_validation->set_rules('zip', "Please put your zip number.", "required|numeric");
            $this->form_validation->set_rules('contact', "Please put your contact number.", "required|numeric");
            $this->form_validation->set_rules('email', "Please put a valid email address.", 'required|valid_email|is_unique[customer.email]');
            $this->form_validation->set_message('required', '{field}');

            if ($this->form_validation->run()) {

                $data = array(
                    'title' => "Checkout - Delivery method",
                    'page' => "Home",
                    'shipper' => $shipper,
                    'fname' => html_escape(trim(ucwords($this->input->post('firstname')))),
                    'lname' => html_escape(trim(ucwords($this->input->post('lastname')))),
                    'address' => html_escape(trim(ucwords($this->input->post('address')))),
                    'province' => html_escape(trim(ucwords($this->input->post('province')))),
                    'city' => html_escape(trim(ucwords($this->input->post('city')))),
                    'barangay' => html_escape(trim(ucwords($this->input->post('barangay')))),
                    'zip' => html_escape(trim($this->input->post('zip'))),
                    'contact' => html_escape(trim($this->input->post('contact'))),
                    'email' => html_escape(trim($this->input->post('email'))),
                    'CTI' => $this->basket->total_items()
                );

                $checkout1_session = array(
                    'fname' => html_escape(trim(ucwords($this->input->post('firstname')))),
                    'lname' => html_escape(trim(ucwords($this->input->post('lastname')))),
                    'address' => html_escape(trim(ucwords($this->input->post('address')))),
                    'province' => html_escape(trim(ucwords($this->input->post('province')))),
                    'city' => html_escape(trim(ucwords($this->input->post('city')))),
                    'barangay' => html_escape(trim(ucwords($this->input->post('barangay')))),
                    'zip' => html_escape(trim($this->input->post('zip'))),
                    'contact' => html_escape(trim($this->input->post('contact'))),
                    'email' => html_escape(trim($this->input->post('email')))
                );

                $this->session->set_userdata('checkout1_session', $checkout1_session);

                $this->load->view('ordering/includes/header', $data);
                $this->load->view('ordering/includes/navbar');
                $this->load->view('ordering/checkout2');
                $this->load->view('ordering/includes/footer');
            }

            else {
                $this->checkout1();
            }

        }

    }

    public function checkout2() {

        $shipper = $this->item_model->fetch('shipper');
        $data = array(
            'title' => "Checkout - Delivery Method",
            'page' => "Home",       
            'CTI' => $this->basket->total_items(),
            'shipper' => $shipper,
        );

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/checkout2');
        $this->load->view('ordering/includes/footer');
    }
  
    public function checkout2_exec() {

        $this->form_validation->set_rules('shipper_id', "Please choose a shipper.", "required");
        $this->form_validation->set_message('required', '{field}');
        $shipper = $this->item_model->fetch('shipper', array('shipper_id' => $this->input->post('shipper_id')))[0];


        if ($this->form_validation->run()) {

            if ($this->session->has_userdata('isloggedin')) {

              $data = array(
                'title' => "Checkout - Payment Method",
                'page' => "Home",
                'CTI' => $this->basket->total_items()
            );

              $checkout2_session = array(
                'shipper_name' => $shipper->shipper_name,
                'shipper_price' => $shipper->shipper_price, 
            );

              $this->session->set_userdata('checkout2_session', $checkout2_session);

          }

          else {

            $data = array(
                'title' => "Checkout - Payment Method",
                'page' => "Home",
                'fname' => html_escape(trim(ucwords($this->input->post('firstname')))),
                'lname' => html_escape(trim(ucwords($this->input->post('lastname')))),
                'address' => html_escape(trim(ucwords($this->input->post('address')))),
                'province' => html_escape(trim(ucwords($this->input->post('province')))),
                'city' => html_escape(trim(ucwords($this->input->post('city')))),
                'barangay' => html_escape(trim(ucwords($this->input->post('barangay')))),
                'zip' => html_escape(trim($this->input->post('zip'))),
                'contact' => html_escape(trim($this->input->post('contact'))),
                'email' => html_escape(trim($this->input->post('email'))),
                'CTI' => $this->basket->total_items()
            );

            $checkout2_session = array(
                'shipper_name' => $shipper->shipper_name,
                'shipper_price' => $shipper->shipper_price, 
            );

            $this->session->set_userdata('checkout2_session', $checkout2_session);

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/checkout4');
        $this->load->view('ordering/includes/footer');
    }
    else {
        $this->checkout2();
    }

}

public function checkout3() {

    $data = array(
        'title' => "Checkout - Order Review",
        'page' => "Home",
        'shipper_name' => html_escape(trim(ucwords($this->input->post('shipper_name')))),
        'shipper_price' => html_escape(trim($this->input->post('shipper_price'))),
        'CTI' => $this->basket->total_items(),
    );

    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    $this->load->view('ordering/checkout3');
    $this->load->view('ordering/includes/footer');
}

public function checkout3_exec() {

    $this->form_validation->set_rules('payment', "Please choose a payment.", "required");
    $this->form_validation->set_message('required', '{field}');

    if ($this->form_validation->run()) {

        if ($this->session->has_userdata('isloggedin')) {

          $data = array(
            'title' => "Checkout - Order Review",
            'page' => "Home",
            'cartItems' => $this->basket->contents(),
            'shipper_name' => html_escape(trim(ucwords($this->input->post('shipper_name')))),
            'shipper_price' => html_escape(trim($this->input->post('shipper_price'))),
            'CT' => $this->basket->total(),
            'CTI' => $this->basket->total_items(),
            'payment' => html_escape($this->input->post('payment'))
        );
      }

      else{
        $data = array(
            'title' => "Checkout - Order Review",
            'page' => "Home",
            'cartItems' => $this->basket->contents(),
            'shipper_name' => html_escape(trim(ucwords($this->input->post('shipper_name')))),
            'shipper_price' => html_escape(trim($this->input->post('shipper_price'))),
            'CT' => $this->basket->total(),
            'CTI' => $this->basket->total_items(),
            'payment' => html_escape($this->input->post('payment')),
            'fname' => html_escape(trim(ucwords($this->input->post('firstname')))),
            'lname' => html_escape(trim(ucwords($this->input->post('lastname')))),
            'address' => html_escape(trim(ucwords($this->input->post('address')))),
            'province' => html_escape(trim(ucwords($this->input->post('province')))),
            'city' => html_escape(trim(ucwords($this->input->post('city')))),
            'barangay' => html_escape(trim(ucwords($this->input->post('barangay')))),
            'zip' => html_escape(trim($this->input->post('zip'))),
            'contact' => html_escape(trim($this->input->post('contact'))),
            'email' => html_escape(trim($this->input->post('email'))),
        );

    }
    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    $this->load->view('ordering/checkout4');
    $this->load->view('ordering/includes/footer');

}

else {
    $this->checkout3();
}

}

public function checkout4() {

    $data = array(
        'title' => "Checkout - Order Review",
        'page' => "Home",
        'shipper_name' => html_escape(trim(ucwords($this->input->post('shipper_name')))),
        'shipper_price' => html_escape(trim($this->input->post('shipper_price'))),
        'cartItems' => $this->basket->contents(),
        'CT' => $this->basket->total(),
        'CTI' => $this->basket->total_items(),
    );

    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    $this->load->view('ordering/checkout4');
    $this->load->view('ordering/includes/footer');
}



public function do_wishlist() {

    if ($this->session->has_userdata('isloggedin')) {

        $customer_id = $this->input->post('customer_id');
        $product_id = $this->input->post('product_id');

        $wish = array(
            'customer_id' => $customer_id,
            'product_id' => $product_id,
        );

        $this->item_model->insertData('wishlist', $wish);

        redirect('Home/wishlist');
    } 

    else {
        redirect('login');
    }
}

public function wishlist() {
    if($this->session->has_userdata('isloggedin')) {

        $wishes = $this->item_model->fetch('wishlist', array('customer_id' => $this->session->uid));

        $data = array(
            'title' => "Wishlist",
            'page' => "Home",
            'wishes' => $wishes,
            'CTI' => $this->basket->total_items()
        );

            // print_r($this->input->post('remove')); die();
        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/menu_account');
        $this->load->view('ordering/wishlist');
        $this->load->view('ordering/includes/footer');
            //echo $this->uri->segment(1) ;
    } else {
        redirect('login');
    }
}

public function delete_wishlist() {

    if ($this->session->has_userdata('isloggedin')) {

        $wishid = array(
            'wishlist_id' => $this->uri->segment(3),
        );

        $res = $this->item_model->delete('wishlist', $wishid);

        redirect('Home/wishlist');

    } else {
        redirect('login');
    }
}

public function customer_orders() {

    if ($this->session->has_userdata('isloggedin')) {

        $this->load->library('pagination');
        $perpage = 10;
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

        $config['base_url'] = base_url() . "home/customer_orders";
        $config['total_rows'] = $this->item_model->getCount('orders', array('customer_id' => $this->session->uid));
        $this->pagination->initialize($config);

        $orders = $this->item_model->getItemsWithLimit('orders', $perpage, $this->uri->segment(3),'order_id', 'DESC',   array('customer_id' => $this->session->uid));

        $data = array(
            'title' => "My Orders",
            'page' => "Home",
            'orders' => $orders,
            'CTI' => $this->basket->total_items(),
            'links' => $this->pagination->create_links()
        );

        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/customer_orders');
        $this->load->view('ordering/includes/footer');
    } else {
        redirect('login');
    }
}

public function customer_order_view() {
    if ($this->session->has_userdata('isloggedin')) {
        $order = $this->item_model->fetch('orders', array('customer_id' => $this->session->uid, 'order_id' => $this->uri->segment(3)))[0];
        $order_items = $this->item_model->fetch('order_items', array('order_id' => $this->uri->segment(3)));
        $order_status = $this->item_model->fetch('order_status', array('customer_id' => $this->session->uid, 'order_id' => $this->uri->segment(3)));

        if (!$order || !$order_items || !$order_status) {
            redirect('home/customer_orders');
        } else {
            $data = array(
                'title' => "Order Status",
                'page' => "Home",
                'order' => $order,
                'order_items' => $order_items,
                'order_status' => $order_status,
                'CTI' => $this->basket->total_items()
            );

            $this->load->view('ordering/includes/header', $data);
            $this->load->view('ordering/includes/navbar');
            $this->load->view('ordering/customer_order_view');
            $this->load->view('ordering/includes/footer');
        }
    } else {
        redirect('login');
    }
}

public function cancel_order() {
    if($this->session->has_userdata('isloggedin')){

        $customer = $this->item_model->fetch("orders", "order_id = " . $this->uri->segment(3))[0];
        if ($customer->process_status == 0 || $customer->process_status == 3 ){
            redirect('home/customer_orders');
        }

        else {
            $cancel = $this->item_model->updatedata("orders", array("status" => 0, "process_status" => 0), "order_id = " . $this->uri->segment(3));
            $restore = $this->item_model->fetch("order_items", array("order_id" => $this->uri->segment(3)));

            foreach ($restore as $restore) {
                $item = $this->item_model->fetch('product', array('product_id' => $restore->product_id))[0];
                $quantity = $item->product_quantity + $restore->quantity;
                $this->item_model->updatedata("product", array("product_quantity" => $quantity), "product_id = " .$restore->product_id);
                $this->item_model->updatedata("order_items", array("status" =>  0), "product_id = " .$restore->product_id);
            }

            if($cancel) {
                $user_id = ($this->session->userdata("type") == 2) ? "customer_id" : "admin_id";
                $for_log = array(
                    "$user_id" => $this->session->uid,
                    "user_type" => $this->session->userdata('type'),
                    "username" => $this->session->userdata('username'),
                    "date" => time(),
                    "action" => 'Cancelled order #' . $this->uri->segment(3)
                );

                $this->item_model->insertData("user_log", $for_log);

                $data = array (
                  "description_status" => "You cancelled your order.",
                  "customer_id" => $customer->customer_id,
                  "order_id" => $customer->order_id,
                  "transaction_date" => time()
              ); 

                $this->item_model->insertData("order_status", $data);

                redirect('home/customer_orders');
            }
        }

    }

    else {
        redirect('login');
    }
}

public function account() {
    if($this->session->has_userdata('isloggedin')){
        $account = $this->item_model->fetch('customer',array('customer_id' => $this->session->uid))[0];

        $data = array(
            'title' => 'My Account',
            'account' => $account,
            'CTI' => $this->basket->total_items(),
            'page' => "Home"
        );
        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        $this->load->view('ordering/menu_account');
        $this->load->view('ordering/account');
        $this->load->view('ordering/includes/footer');
    }

    else{
        redirect('login');
    }
}

public function save_changes() {

    if($this->session->has_userdata('isloggedin')){

        $this->form_validation->set_rules('firstname', "Please put your first name.", "required");
        $this->form_validation->set_rules('lastname', "Please put your lastname name.", "required");
        $this->form_validation->set_rules('complete_address', "Please put your complete address.", "required");
        $this->form_validation->set_rules('province', "Please put the name of your province.", "required");
        $this->form_validation->set_rules('city', "Please put the name of your City / Municipality.", "required");
        $this->form_validation->set_rules('barangay', "Please put the name of your barangay.", "required");
        $this->form_validation->set_rules('zip', "Please put your zip number.", "required|numeric");
        $this->form_validation->set_rules('contact', "Please put your contact number.", "required|numeric");

        $this->form_validation->set_message('required', '{field}');

        if ($this->form_validation->run()) {

            $data = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'complete_address' => $this->input->post('complete_address'),
                'province' => $this->input->post('province'),
                'city_municipality' => $this->input->post('city'),
                'barangay' => $this->input->post('barangay'),
                'zip_code' => $this->input->post('zip'),
                'contact_no' => $this->input->post('contact')
            );

            $update = $this->item_model->updatedata('customer', $data, array('customer_id' => $this->session->uid));

            $for_log = array(
                "customer_id" => html_escape($this->session->uid),
                "user_type" => html_escape($this->session->userdata('type')),
                "username" => html_escape($this->session->userdata('username')),
                "date" => html_escape(time()),
                "action" => html_escape('Updated his/her profile.'),
                'status' => html_escape('1')
            );

            $this->item_model->insertData('user_log', $for_log);

            $statusMsg = $update?'Your profile has been updated successfully.':'A problem has occured, please try again';
            $this->session->set_userdata('statusMsg', $statusMsg);


            redirect("home/account");               
        }

        else{
            $this->account();
        }
    }

    else{
        redirect('login');
    }
}

public function contact() {
    $data = array(
        'title' => 'Contact us',
        'CTI' => $this->basket->total_items(),
        'page' => "Home"
    );
    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    $this->load->view('ordering/contact');
    $this->load->view('ordering/includes/footer');
}

public function faq() {
    $data = array(
        'title' => 'FAQ',
        'CTI' => $this->basket->total_items(),
        'page' => "Home"
    );
    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    $this->load->view('ordering/faq');
    $this->load->view('ordering/includes/footer');
}

public function add() {
    $data = array(
        'id' => $this->input->post("product_id"),
        'name' => $this->input->post("product_name"),
        'img' => $this->input->post("product_img"),
        'price' => $this->input->post("product_price"),
        'qty' => $this->input->post("min_quantity"),
        'maxqty' => $this->input->post("max_quantity")
    );
    $insert = $this->basket->insert($data);
}

public function update() {
    $data = array(
        'rowid' => $this->input->post("row_id"),
        'qty' => $this->input->post("product_quantity"),
        'maxqty' => $this->input->post("max_quantity")
    );

    $this->basket->update($data);
}

public function remove() {
    $this->basket->remove($this->input->post("row_id"));
}

public function post() {

    $this->form_validation->set_rules('rating', "Please put a rating.", "required");
    $this->form_validation->set_rules('feedback', "Please put a feedback.", "required");
    $this->form_validation->set_message('required', '{field}');

    if ($this->form_validation->run()) {
        $data = array(
            'customer_id' => $this->session->uid,
            'product_id' => $this->input->post("product_id"),
            'feedback' => $this->input->post("feedback"),
            'rating' => $this->input->post("rating"),
            'added_at' => time()
        );

        $post = $this->item_model->fetch("feedback", array('customer_id' => $this->session->uid, 'product_id' => $this->input->post("product_id"), 'status' => 1));

        if ($post) {
            $this->item_model->updatedata("feedback", $data, array('customer_id' => $this->session->uid, 'product_id' => $this->input->post("product_id")));
        } else {
            $this->item_model->insertData("feedback", $data);   
        }

        $rating = $this->item_model->avg('feedback', 'product_id = ' . $this->input->post("product_id"). ' AND status = 1', 'rating');

        $for_rating = array(
            'product_rating' => $rating->rating,
        );

        $this->item_model->updateData("product", $for_rating, array('product_id' => $this->input->post("product_id")));

        $user_id = ($this->session->userdata("type") == 2) ? "customer_id" : "admin_id";
        $for_log = array(
            "$user_id" => $this->db->escape_str($this->session->uid),
            "user_type" => $this->db->escape_str($this->session->userdata('type')),
            "username" => $this->db->escape_str($this->session->userdata('username')),
            "date" => $this->db->escape_str(time()),
            "action" => $this->db->escape_str('Commented on product ' . $_POST["product_name"] . ' and rated it ' . $_POST["rating"]),
            'status' => $this->db->escape_str('1')
        );

        $this->item_model->insertData('user_log', $for_log);
    }
}

public function placeorder() {
        // if logged in
    if ($this->session->has_userdata('isloggedin')) {
        $userinformation = $this->item_model->fetch('customer', array('customer_id' => $this->session->uid))[0];
        $CT = $this->basket->total() + $this->input->post('shipper_price');

        $data = array(
            'customer_id' => $this->session->uid,
            'total_price' => $CT,
            'order_quantity' => $this->basket->total_items(),
            'transaction_date' => time(),
            'delivery_date' => time() + 259200,
            'shipping_address' => "$userinformation->complete_address, $userinformation->barangay, $userinformation->city_municipality, $userinformation->province",
            'payment_method' => html_escape($this->input->post('payment'))
        );

        $order_id = $this->item_model->insert_id('orders', $data);
            // get cart items
        $basketItems = $this->basket->contents();
            // loop
        foreach ($basketItems as $item) {
            $data = array(
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'product_subtotal' => $item['subtotal'],
                'product_image1' => $item['img'],
                'quantity' => $item['qty']
            );
            $this->item_model->insertData('order_items', $data);

            $for_audit = array(
                "customer_name" => $this->session->userdata("username"),
                "item_name" => $item['name'],
                "at_detail" => "Purchase",
                "at_date" => time(),
                    "customer_id" => $this->session->uid, # logged in
                    "product_id" => $item['id'],
                    "order_id" => $order_id
                    # status has a default value of 1
                );
            $this->item_model->insertData("audit_trail", $for_audit);

            $stock = $item['maxqty'] - $item['qty'];
            $data1 = array(
                'product_quantity' => $stock
            );
            $this->item_model->updatedata("product", $data1, array('product_id' => $item['id']));
        }

        $for_orderstatus = array (
          "description_status" => "Your item(s) is being packed and ready for shipment at our merchant's warehouse.",
          "customer_id" => $this->session->uid,
          "order_id" => $order_id,
          "transaction_date" => time()
      ); 

        $this->item_model->insertData("order_status", $for_orderstatus);
        $this->session->set_userdata('statusMsg', 'You have successfully placed an order, an email will be sent at <b>'.$userinformation->email.'</b>.');
    }
        // if not
    else {
        $bytes_code = openssl_random_pseudo_bytes(30, $crypto_strong);
        $hash_code = bin2hex($bytes_code);
        $user_and_pass = substr($this->input->post('firstname'), 0, 1) . substr($this->input->post('lastname'), 0, 1) . time();

            //must put username and password
        $account = array(
            'email' => $this->input->post('email'),
            'username' => $user_and_pass,
            'password' => $this->item_model->setPassword($user_and_pass, $hash_code),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'complete_address' => $this->input->post('address'),
            'province' => $this->input->post('province'),
            'city_municipality' => $this->input->post('city'),
            'barangay' => $this->input->post('barangay'),
            'zip_code' => $this->input->post('zip'),
            'contact_no' => $this->input->post('contact'),
            'image' => "default-user.png",
            'status' => "1",
            'registered_at' => time(),
            'verification_code' => $hash_code
        );
            //returns the id of last query
        $customer_id = $this->item_model->insert_id('customer', $account);

        $data = array(
            'customer_id' => $customer_id,
            'total_price' => $this->basket->total(),
            'order_quantity' => $this->basket->total_items(),
            'transaction_date' => time(),
            'delivery_date' => time() + 259200,
            'shipping_address' => $this->input->post('address'),
            'payment_method' => $this->input->post('payment')
        );

        $order_id = $this->item_model->insert_id('orders', $data);
            // get cart items
        $basketItems = $this->basket->contents();
            // loop
        foreach ($basketItems as $item) {
            $data = array(
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'product_subtotal' => $item['subtotal'],
                'product_image1' => $item['img'],
                'quantity' => $item['qty']
            );
            $this->item_model->insertData('order_items', $data);

            $for_audit = array(
                "customer_name" => $user_and_pass,
                "item_name" => $item['name'],
                "at_detail" => "Purchase",
                "at_date" => time(),
                "customer_id" => $customer_id,
                "product_id" => $item['id'],
                "order_id" => $order_id
                    # status has a default value of 1
            );
            $this->item_model->insertData("audit_trail", $for_audit);

            $stock = $item['maxqty'] - $item['qty'];
            $data1 = array(
                'product_quantity' => $stock
            );
            $this->item_model->updatedata("product", $data1, array('product_id' => $item['id']));
        }

        $for_orderstatus = array (
          "description_status" => "Your item(s) is being packed and ready for shipment at our merchant's warehouse.",
          "customer_id" => $customer_id,
          "order_id" => $order_id,
          "transaction_date" => time()
      ); 

        $this->item_model->insertData("order_status", $for_orderstatus);
        $this->session->set_userdata('statusMsg', 'You have successfully placed an order, an email will be sent at <b>'.$this->input->post('email').'</b>.');
    }
    $this->session->unset_userdata('checkout1_session');
    $this->session->unset_userdata('checkout2_session');
    $this->basket->destroy();
    $this->index();
}

    public function recovery() {
    $data = array(
        'page' => "Home",
        'title' => 'Data Recovery',
        'CTI' => $this->basket->total_items()
    );
    $this->load->view('ordering/includes/header', $data);
    $this->load->view('ordering/includes/navbar');
    // $this->load->view('ordering/menu_account');
    $this->load->view('ordering/recovery');
    $this->load->view('ordering/includes/footer');
}

    public function repair() {
        $data = array(
            'page' => "Home",
            'title' => 'Apple Repair',
            'CTI' => $this->basket->total_items()
        );
        $this->load->view('ordering/includes/header', $data);
        $this->load->view('ordering/includes/navbar');
        // $this->load->view('ordering/menu_account');
        $this->load->view('ordering/repair');
        $this->load->view('ordering/includes/footer');
    }
}

?>
