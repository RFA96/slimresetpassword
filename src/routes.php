<?php
date_default_timezone_set("Asia/Jakarta");
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->post('/resetpassword', function (Request $request, Response $response){
        $mail = new PHPMailer(true);
        $jsonParams = $request->getParsedBody();

        $sqlCheckUsernameAndEmail = $this->db->query("SELECT user_login_id, user_email, full_name FROM scoring_dba.users WHERE user_login_id = '".$jsonParams['username']."' AND user_email = '".$jsonParams['email']."'");
        $rowCheckUsernameAndEmail = $sqlCheckUsernameAndEmail->fetchAll();
        if($sqlCheckUsernameAndEmail->rowCount() == 1)
        {
            try
            {
                $mail->isSMTP();
                $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = 'pefindotest@gmail.com';
                $mail->Password = 'pefindoadmin123';
                $mail->setFrom("pefindotest@gmail.com", "No Reply Pefindo Admin");
                $mail->addAddress($jsonParams['email'], $rowCheckUsernameAndEmail[0]['full_name']);
                //Mail Content
                $mail->isHTML(true);
                $mail->Subject = "Test dari PHPMailer";
                $mail->Body = "Berhasil mengirimkan email dari <b>PHPMailer</b> menggunakan <i>framework</i> Slim 3";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                if($mail->send())
                {
                    return $response->withJson(["status" => "success", "message" => "Reset Password has been sent to ".$jsonParams['email']."!"])->withStatus(200)->withHeader('Content-Type', 'application/json');
                }
                else
                {
                    return $response->withJson(["status" => "error", "message" => $mail->ErrorInfo])->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            }
            catch (Exception $e)
            {
                return $response->withJson(["status" => "error", "message" => $mail->ErrorInfo])->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }
        else
        {
            return $response->withJson(["status" => "error", "message" => "Username and email not match. Please try again!"])->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    });
};
