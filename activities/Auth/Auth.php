<?php

namespace Auth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use database\Database;


class Auth
{

    public function redirect($url)
    {
        header('Location: ' . trim(CURRENT_DOMAIN, '/') . '/' . trim($url, '/ '));
        exit;
    }

    public function redirect_back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function hash($password)
    {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        return $hash_password;
    }

    private function random()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function activation_message($username, $verify_token)
    {
        $message =
            'فعال ساز حساب کاربری' .
            '<p>' . $username . ' عزیز برای فعال سازی حساب کاربری خود لطفا روی لینک زیر کلیک نمایید</p>' .
            '<div><a href="' . url('activation/' . $verify_token) . '">فعال سازی حساب</a></div>';
        return $message;
    }

    public function send_email($email_address, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->CharSet = "UTF-8"; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = MAIL_HOST; //Set the SMTP server to send through
            $mail->SMTPAuth = SMTP_AUTH; //Enable SMTP authentication
            $mail->Username = MAIL_USERNAME; //SMTP username
            $mail->Password = MAIL_PASSWORD; //SMTP password
            $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
            $mail->Port = MAIL_PORT; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(SENDER_MAIL, SENDER_NAME);
            $mail->addAddress($email_address);

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo 'Message has been sent';
            return true;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }


    public function register()
    {
        require_once(BASE_PATH . '/template/auth/register.php');
    }

    public function register_store($request)
    {
        if (empty($request['emil']) || empty($request['username']) || empty($request['password'])) {
            flash('register_error', 'تمامی فیلد ها اجباری میباشد');
            $this->redirect_back();
        } else if (strlen($request['password']) < 8) {
            flash('register_error', 'رمز عبور حداقل باید هشت کاراکتر باشد');
            $this->redirect_back();
        } else if (filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            flash('register_error', 'ایمیل شما نامعتبر است');
            $this->redirect_back();
        } else {
            $db = new Database();
            $user = $db->select('SELECT * FROM users WHERE email = ?', [$request['email']])->fetch();
            if ($user != null) {
                flash('register_error', 'کاربر از قبل در سیستم وجود دارد');
                $this->redirect_back();
            } else {
                $random_token = $this->random();
                $activation_message = $this->activation_message($request['username'], $random_token);
                $result = $this->send_email($request['email'], 'فعال سازی حساب کاربری', $activation_message);
                if ($result) {
                    $request['verify_token'] = $random_token;
                    $request['password'] = $this->hash($request['password']);
                    $db->insert('users', array_keys($request), $request);
                    $this->redirect('login');
                } else {
                    flash('register_error', 'ثبت نام با خطا مواجه شد');
                    $this->redirect_back();
                }
            }
        }
    }


    public function activation($verify_token)
    {
        $db = new Database();
        $user = $db->select('SELECT * FROM users WHERE verify_token = ? AND is_active = 0;', [$verify_token])->fetch();
        if ($user == null) {
            $this->redirect('login');
        } else {
            $result = $db->update('users', $user['id'], ['is_active'], [1]);
            $this->redirect('login');
        }
    }


    public function login()
    {
        require_once(BASE_PATH . '/template/auth/login.php');
    }


    public function check_login($request)
    {
        if (empty($request['email']) || empty($request['password'])) {
            flash('login_error', 'تمامی فیلد ها الزامی میباشد');
            $this->redirect_back();
        } else {
            $db = new Database();
            $user = $db->select('SELECT * FROM users WHERE email = ?', [$request['email']])->fetch();
            if ($user != null) {
                if (password_verify($request['password'], $user['password']) && $user['is_active'] == 1) {
                    $_SESSION['user'] = $user['id'];
                    $this->redirect('admin');
                } else {
                    flash('login_error', 'ورود انجام نشد');
                    $this->redirect_back();
                }
            } else {
                flash('login_error', 'کاربری با این مشخصات یافت نشد');
                $this->redirect_back();
            }
        }
    }

    public function check_admin()
    {
        if (isset($_SESSION['user'])) {
            $db = new Database();
            $user = $db->select('SELECT * FROM users id = ?', [$_SESSION['user']])->fetch();
            if ($user != null) {
                if ($user['premission'] != 'admin') {
                    $this->redirect('home');
                }
            } else {
                $this->redirect('home');
            }
        } else {
            $this->redirect('home');
        }
    }


    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            session_Destroy();
        }
        $this->redirect('home');
    }

    public function forgot()
    {
        require_once(BASE_PATH . '/template/auth/forgot.php');
    }


    public function forgot_request($request)
    {
        if (empty($request['email'])) {
            flash('forgot_error', 'لطفا ایمیل را وارد کنید');
            $this->redirect_back();
        } else if (!filter_Var($request['email'], FILTER_VALIDATE_EMAIL)) {
            flash('forgot_error', 'ایمیل معتبری وارد نشده است');
            $this->redirect_back();
        } else {
            $db = new Database();
            $user = $db->select('SELECT * FROM users WHERE email = ?', [$request['email']])->fetch();
            if ($user == null) {
                flash('forgot_error', 'کاربری یافت نشد');
                $this->redirect_back();
            } else {
                $random_token = $this->random();
                $forgot_message = $this->forgot_message($user['username'], $random_token);
                $result = $this->send_email($request['email'], 'فعال سازی حساب کاربری', $forgot_message);
                if ($result) {
                    date_default_timezone_set('Asia/Tehran');
                    $db->update('users', $user['id'], ['forgot_token', 'forgot_token_expire'], [$random_token, date('Y-m-d H:i:s', strtotime('+15 minutes'))]);
                    $this->redirect('login');
                } else {
                    flash('forgot_error', 'ارسال ایمیل انجام نشد');
                    $this->redirect_back();
                }
            }
        }
    }

    public function forgot_message($username, $forgot_token)
    {
        $message =
            'فراموشی رمز عبور' .
            '<p>' . $username . ' عزیز برای تغیر رمز عبور حساب کاربری خود لطفا روی لینک زیر کلیک نمایید</p>' .
            '<div><a href="' . url('reset-password-form/' . $forgot_token) . '">بازیابی رمز عبور</a></div>';
        return $message;
    }

    public function reset_password_view($forgot_token) {
        require_once(BASE_PATH . '/template/auth/resetpassword.php');
    }

    public function reset_password($request , $forgot_token) {
        if(!isset($request['password']) || strlen($request['password']) < 8) {
            flash('reset_error', 'رمز عبور بیش از هشت کاراکتر باشد');
            $this->redirect_back();
        }
        else {
            $db = new Database();
            $user = $db->select('SELECT * FROM user WHERE forgot_token = ?',[$forgot_token])->fetch();
            if($user == null) {
                flash('reset_error', 'کاربر یافت نشد');
                $this->redirect_back();
            }
            else {
                if($user['forgot_token_expire'] < date('Y-m-d H:i:s')) {
                    flash('reset_error', 'تاریخ توکن معتبر نیست');
                    $this->redirect_back();
                }
                
                if($user) {
                    $db->update('users' , $user['id'] , ['password'] , $this->hash($request['password']));
                    $this->redirect('login');
                }
            }
        }
    }

}

?>