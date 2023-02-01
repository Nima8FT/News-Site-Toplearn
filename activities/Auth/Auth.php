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

    private function send_email($email_address, $subject, $body)
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
        if($user == null) {
            $this->redirect('login');
        }
        else {
            $result = $db->update('users', $user['id'], ['is_active'], [1]);
            $this->redirect('login');
        }
    }

}

?>