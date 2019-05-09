<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ControllerPages extends CI_Controller
{

    //Php Constructor
    public function __construct()
    {
        //Calls contructor from Ci_Controller
        parent::__construct();
        $this->load->model('ModelAccountOperation');
    }

    public function view($page = "home")
    {

        if (!file_exists(APPPATH . "views/pages/{$page}.php")) {
            show_404();
        }

        $data["title"] = $page;

        $this->load->view("templates/header", $data);
        $this->load->view("pages/{$page}");
        $this->load->view("templates/footer", $data);
    }

    public function login()
    {
        if ($this->session->userdata('emmail')) {
            redirect('backoffice');
        }

        $config = array(
            array(
                "field" => "email",
                "label" => "Email",
                "rules" => "required|trim"
            ),
            array(
                "field" => "password",
                "label" => "Password",
                "rules" => "required|trim"
            )
        );
        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            redirect();
        }

        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password"))
        );

        $result = $this->ModelAccountOperation->Validate_Data($data);

        if ($result) {
            $sessionData = array(
                "email" => $result[0]->email
            );

            $this->session->set_userdata($sessionData);

            redirect('backoffice');
        };

        redirect();
    }

    public function createAccount()
    {
        $config = array(
            array(
                "field" => "userName",
                "label" => "userName",
                "rules" => "required|trim"
            ),
            array(
                "field" => "email",
                "label" => "Email",
                "rules" => "required|trim"
            ),
            array(
                "field" => "password",
                "label" => "Password",
                "rules" => "required|trim"
            )
        );

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('incorrectData', 'Fill in all the Fields.');
            redirect('createAccount');
        }

        $data = array(
            "nome" => $this->input->post("userName"),
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password"))
        );

        $this->ModelAccountOperation->insertData($data);

        $this->session->set_flashdata('validData', 'Account Created.');
        redirect('createAccount');
    }

    public function forgotPassword()
    {
        $config = array(
            array(
                "field" => "email",
                "rules" => "required|trim"
            ),
            array(
                "field" => "password",
                "rules" => "required|trim"
            ),
            array(
                "field" => "password2",
                "rules" => "required|trim|matches[password]"
            )
        );

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('incorrectData', 'Fill in the fields or passwords don`t match.');
            redirect('forgotPassword');
        }

        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password"))
        );

        $this->ModelAccountOperation->changePassword($data);

        $this->session->set_flashdata('validData', 'Password Changed.');
        redirect('forgotPassword');
    }

    public function storeMovie()
    {
        $config = array(
            array(
                "field" => "imgMovie",
            ),
            array(
                "field" => "title",
                "rules" => "required|trim"
            ),
            array(
                "field" => "movie",
                "rules" => "required|trim"
            ),
            array(
                "field" => "descricao",
                "rules" => "required|trim"
            )
        );

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('incorrectData', 'Fill in all the fields.');
            redirect('adicionaFilme');
        }

        $data = array(
            "foto" => $this->input->post("imgMovie"),
            "titulo" => $this->input->post("title"),
            "filme" => $this->input->post("movie"),
            "descricao" => $this->input->post("descricao")
        );

        $this->ModelAccountOperation->addMovie($data);

        $this->session->set_flashdata('validData', 'Movie Added');

        redirect('adicionaFilme');
    }

    
}