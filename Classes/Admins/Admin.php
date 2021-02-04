<?php  

class admin {
	public $connection;
	public function __construct(){
		$this->connection = new mysqli('localhost','root','','students-management');
	}

		// Admin Registration 
		public function adminRegistration($uname, $uemail, $upass){
			$sql = "INSERT INTO admins ( user_name , admin_email, admin_password, status) VALUES ('$uname','$uemail','$upass', 'inactive')";
			$data = $this -> connection -> query($sql);	

			if( $data ){
				return true;
			}

		}

		// Username check  
		public function checkUsername($uname){
			$sql = "SELECT * FROM admins WHERE user_name='$uname'";
			$data = $this -> connection -> query($sql);
			$num_of_rows = $data -> num_rows;

			if( $num_of_rows > 0 ){
				return false;
			}else{
				return true;
			}
		}

		// Check Email 
		public function checkEmail($uemail){
			$sql = "SELECT * FROM admins WHERE admin_email='$uemail'";
			$data = $this -> connection -> query($sql);
			$num_of_rows = $data -> num_rows;

			if( $num_of_rows > 0 ){
				return false;
			}else{
				return true;
			}
		}

		// Admin Login System 
		public function adminLogin($euname, $pass){
			$sql = "SELECT * FROM admins WHERE user_name='$euname' OR admin_email='$euname' ";
			$data = $this -> connection -> query($sql);
			$num_of_rows = $data -> num_rows;

			if( $num_of_rows == 1 ){
				$single_user_data = $data  -> fetch_assoc();


				if( password_verify( $pass , $single_user_data['admin_password'] ) == true ){
					session_start();
					$_SESSION['uname'] = $single_user_data['user_name'];
					$_SESSION['email'] = $single_user_data['admin_email'];

					header("location:dashboard.php");

				}else{
					return "<p class='alert alert-danger'>Password is incorrect !<button class='close' data-dismiss='alert'>&times;</button></p>";
				}


			}else{				
				return "<p class='alert alert-danger'>Username or Email is incorrect !<button class='close' data-dismiss='alert'>&times;</button></p>";
			}			

		}

		// Upload profile photo
		public function uploadProfilePhoto($u_pic_name, $ppic_tmp, $uname){
			$sql = "UPDATE admins SET admin_photo = '$u_pic_name'  WHERE  user_name= '$uname' ";
			$data = $this -> connection -> query($sql);
			move_uploaded_file( $ppic_tmp , 'admin_photos/' . $u_pic_name );

		}

		// Show user Photo
		public function showProfilePicture($uname){
			$sql = "SELECT * FROM admins WHERE user_name='$uname'";
			$data = $this -> connection -> query($sql);

			return  $data -> fetch_assoc();

		}
	}
?>