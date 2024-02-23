<?php

class Controller_login extends Controller
{

    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_login_form();
    }

    public function action_login_form()
    {
        $this->render("login");
    }

    /**
     * Vérifie que le mot de passe correspond au mail
     * @return void
     */
    public function action_check_pswd()
    {
        $db = Model::getModel();

        // Sanitize the $_POST values.
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Check if values are set.
        if (isset($email) && isset($password)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msg = "Ce n'est pas un email correcte !";
            } else {
                $msg = "L'identifiant ou le mot de passe est incorrect !";

                if ($db->checkMailPassword($email, $password)) {
                    $role = $db->hasSeveralRoles();
                    if (isset($role['roles'])) {
                        $msg = $role;
                    } else {
                        // Use output buffering to avoid header modification warning.
                        ob_start();
                        header("Location: index.php?controller=$role&action=default");
                        ob_end_flush();
                        return;
                    }
                }
            }

            $data = ['response' => $msg];
            $this->render('login', $data);
        }
    }

    /**
     * Cette fonction va être appelée eu fur et à mesure que l'utilisateur tape son email afin de lui indiquer si son email existe
     * Elle vérifie si l'email existe dans la base de donnée, renvoie true si oui, false sinon
     * @return bool
     */
    public function action_check_mail()
    {
        $mailExisting = false;

        if (isset($_POST['email'])) {
            $mail = e($_POST['email']);
            //à chiffrer
            $bd = Model::getModel();
            $mailExisting = $bd->mailExists($mail);
        }

        return $mailExisting;
    }

}

