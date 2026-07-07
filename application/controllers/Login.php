<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('M_Login', 'Mlog');
	}

	public function index()
	{
		if($this->session->userdata('authenticated')) // Jika user sudah login (Session authenticated ditemukan)
		{
			
			redirect('Dashboard'); //Redirect ke Dashboard UMUM
			
		}
		
		$this->form_validation->set_rules('input_user','Username','required|trim'); // Required Username
		$this->form_validation->set_rules('input_password','Password','required|trim'); // Required Pass

		if($this->form_validation->run() == false)
		{
			$data['title'] = 'Login Form';

			$this->load->view('templates/auth_header',$data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		}
		else
		{
			//Jika Validasi User name dan password Sukses
			$this->_login();
		}
	}


	private function _login ()
	{
		
		$username = $this->input->post('input_user');
		$password = $this->input->post('input_password');

		$data_user = $this->db->get_where('z_master_user',['username' => $username])->row_array();
		
		if($data_user)
		{

			//Jika User Active
			if($data_user['is_active'] == 1)
			{
				//if($data_user['logged_in'] == 0)
				//{
					//cek password
					if(password_verify($password,$data_user['password']))
					{
						$data =[
								'authenticated'=>true,
								'id'=> $data_user['id_user'],
								'username'=> $data_user['username'],
								// 'role_id' => $data_user['role_id'],
								'caption' => $data_user['caption'],
								'group_user' => $data_user['group_user'],
								'department' => $data_user['department'],
								'vendor' => $data_user['vendor'],
								'id_cost_center' => $data_user['id_cost_center']
						];

						$upd_log = array('logged_in' => 1,);
						$where = "id_user = '".$data_user['id_user']."'"; 
						$this->db->update('master_user', $upd_log, $where);
						$this->session->set_userdata($data);

						redirect('Dashboard'); //Redirect ke Dashboard UMUM
							
					}
					else
					{
						
						$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
						Wrong Password !!
						</div>');
						//echo 'Login';
						redirect('Login');
					}
				/*}
				else
				{

					$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					Username Ini telah Login !! 
					</div>');
					redirect('Login');
				}*/

			}
			else
			{
				//echo $user;
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				Username Belum Teraktivasi, Cek Email  !!
				</div>');
				redirect('Login');
			}
		}
		else	
		{
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				Username Belum Terdaftar !!
				</div>');
			redirect('Login');	
		}
	}


	public function registration()
	{
		$this->form_validation->set_rules('input_username','Username','required|trim|is_unique[z_master_user.username]',
			[
				'is_unique' => 'Username Already Used !'
			]);
		$this->form_validation->set_rules('input_reg_firstname','Firstname','required|trim');
		$this->form_validation->set_rules('input_reg_lastname','Last_name','required|trim');
		$this->form_validation->set_rules('input_reg_email','Email','required|trim|valid_email|is_unique[z_master_user.email]',[
				'is_unique' => 'This Email Already register !'
		]);
		$this->form_validation->set_rules('input_reg_password','Password','required|trim|min_length[3]|matches[input_reg_confpassword]',[
			'matches' => 'Password Tidak Sama !', 
			'min_length' => 'Password Terlalu pendek !'
		]);
		$this->form_validation->set_rules('input_reg_confpassword','ConfirmPassword','required|trim|matches[input_reg_password]');

		if($this->form_validation->run() == false)
		{
			$data['title'] = 'Registration Form';
			$data['dept'] = $this->Mlog->getDept()->result();
			$data['vendor'] = $this->Mlog->getVendor()->result();

			$this->load->view('templates/auth_header',$data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		}
		else
		{
			$user_email = $this->input->post('input_reg_email',true);
			$data = [

					'date_created' => time(),
					'username' => htmlspecialchars($this->input->post('input_username',true)),
					'password' => password_hash($this->input->post('input_reg_password'),
					PASSWORD_DEFAULT),
					'group_user' => 'member',
					'caption' =>  htmlspecialchars($this->input->post('input_reg_firstname',true)) ." ". htmlspecialchars($this->input->post('input_reg_lastname',true)),
					'id_cost_center' => 'default',
					'nik' => 'default',
					'reset_password' => 'y',
					'active' => 'y',
					'department' => 'default',
					'vendor' => 'default',
					'no_reg' => 'default',
					'no_staff' => 'default',
					'level' => 0,
					'role_id' => 0,
					'is_active' => 0,
					'email' =>  htmlspecialchars($user_email),
					'logged_in' => 0,

			];

			//token 

			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $user_email,
				'token' => $token,
				'date_created' => time()
			];
			

			$this->db->insert('master_user',$data);
			$this->db->insert('master_user_token',$user_token);

			$this->_sendEmail($token,'verify') ;

			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
				User Baru Berhasil Dibuat, Silahkan Untuk Aktivasi Akun !!
				</div>');
			redirect('Login');
		}

		
	}

	private function _sendEmail($token, $type)
	{
		$config = [
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_user' => 'setomenggolo87@gmail.com',
			'smtp_pass' => 'jarot1991',
			'smtp_port' => 465,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		];

		$this->email->initialize($config);

		$this->email->from('setomenggolo87@gmail.com','CPI Online');
		$this->email->to($this->input->post('input_reg_email'));

		if($type == 'verify')
		{
			$this->email->subject('Account Verification CP Online');
			$this->email->message('Click this link to verify you account : <a href="'. base_url() . 'Login/verify?email=' . $this->input->post('input_reg_email') .'&token='. urldecode($token).  '">Activate</a>');
		}

		

		if($this->email->send())
		{
			return true ;
		}
		else
		{
			echo $this->email->print_debugger();
			die;
		}

	}

	public function verify()
	{
		$user_email = $this->input->get('email');
		$token = $this->input->get('token');

		$user_info_email = $this->db->get_where('master_user',['email' => $user_email ])->row_array();

		if($user_info_email)
		{
			$user_info_token = $this->db->get_where('master_user_token',['token' => $token ])->row_array();

			if($user_info_token)
			{
				if(time() - $user_info_token['date_created'] < (60 * 60 * 24))
				{
					$this->db->set('is_active',1);
					$this->db->where('email', $user_email);
					$this->db->update('master_user');

					$this->db->delete('master_user_token',['email' => $user_email]);

					$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
					'.$user_email.' Telah Di aktivasi
					</div>');
					redirect('Login');
				}
				else
				{
					$this->db->delete('master_user',['email' => $user_email]);
					$this->db->delete('master_user_token',['email' => $user_email]);

					$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
					Aktivasi Akun Gagal, Token Telah Kadaluarsa  !!
					</div>');
					redirect('Login');
				}
			}
			else
			{
				$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				Akctivasi Akun Gagal, Token Salah  !!
				</div>');
				redirect('Login');
			}

		}
		else
		{
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
				Akctivasi Akun Gagal , Email Salah!!
				</div>');
			redirect('Login');

		}
	}

	/*public function forgot_password()
	{
		$data['title'] = 'Forgot Password';

		$this->form_validation->set_rules('input_forgot_email_pass','Email','required|trim|valid_email');

		if($this->form_validation->run() == false)
		{
			$this->load->view('templates/auth_header',$data);
			$this->load->view('auth/forgot_password');
			$this->load->view('templates/auth_footer');
		}
		else
		{
			$email_forgot_pass = $this->input->post('input_forgot_email_pass');

			$user_email = $this->db->get_where('master_user',['email' => $email_forgot_pass, 'is_active' => 1])->row_array();
			//var_dump($this->db->last_query());
			//die;
			
			if($user_email)
			{
				$token = base64_encode(random_bytes(32));
				$user_token = [
					'email' => $email_forgot_pass,
					'token' => $token,
					'date_created' => time()
				];
				$this->db->insert('m_user_token',$user_token);

				$this->_sendEmail($token,'forgot');

				$this->session->set_flashdata('message','<div class="alert alert-warning role="alert">
				Email berhasil dikirim silahkan cek email anda di folder Spam !
				</div>');


				redirect('Login/forgot_password');
			}
			else
			{
				$this->session->set_flashdata('message','<div class="alert alert-danger role="alert">
				Email tidak terdaftar atau belum teraktivasi !!
				</div>');

				redirect('Login/forgot_password');
				
			}
			

		}
	}*/

	public function change_password()
	{
		$data['title'] = "Change Password";

		$data['user'] = $this->db->get_where('master_user',['username' => $this->session->userdata('username')])->row_array();
		$data['group_user'] = $this->db->get_where('master_alias',['ori' => $this->session->userdata('group_user')])->row_array();
		$this->load->vars($data);
		
		//var_dump();
		//die;

		$this->form_validation->set_rules('input_current_password','Current Password','required|trim');
    	$this->form_validation->set_rules('input_new_password','New Password','required|trim|min_length[3]|matches[input_repeat_password]');
    	$this->form_validation->set_rules('input_repeat_password','Repeat Password','required|trim|min_length[3]|matches[input_new_password]');

    	if($this->form_validation->run() == false)
		{
	        $this->load->view('templates/dash_header',$data);
			$this->load->view('templates/dash_sidebar',$data);
			$this->load->view('auth/change_password',$data);
		}
		else
		{
			$current_password = $this->input->post('input_current_password');
			$new_password = $this->input->post('input_new_password');
			$repeat_password = $this->input->post('input_repeat_password');

			if(!password_verify($current_password,$data['user']['password']))
			{
				$this->session->set_flashdata('message','<div class="alert alert-danger role="alert">
				Kata sandi saat ini Salah!!
				</div>');
				redirect('Login/change_password');
			}
			else if($current_password == $new_password)
			{
				$this->session->set_flashdata('message','<div class="alert alert-warning role="alert">
				Kata Sandi baru tidak boleh sama dengan yang lama !!
				</div>');

				redirect('Login/change_password');
			}
			else
			{
				$password_hash = password_hash($new_password,PASSWORD_DEFAULT);
				$this->db->set('password',$password_hash);
				$this->db->where('id_user',$this->session->userdata('id')); 
				$this->db->update('master_user');
				$this->session->set_flashdata('message','<div class="alert alert-success role="alert">
				Kata sandi berhasil dirubah !!
				</div>');

				redirect('Login/change_password');	
			}
		}
	}


	public function logout()
	{

		$upd_log = array('logged_in' => 0,);
		$where = "id_user = '".$this->session->userdata('id')."'"; 
		$this->db->update('master_user', $upd_log, $where);

		$this->session->unset_userdata('id');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role_id');
		$this->session->unset_userdata('group_user');
		$this->session->unset_userdata('caption');
		$this->session->unset_userdata('department');
		$this->session->unset_userdata('vendor');
		$this->session->unset_userdata('id_cost_center');

		
		$this->session->sess_destroy();
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
				Anda Telah Berhasil Logout !!
				</div>');

		
		redirect('Login');

	}
}
