<?php

namespace gamegam\PHPMailer;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class PHPMailerCommand extends Command
{

    public function __construct(private Loader $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("email", "mailer command");
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!isset($args[0]) || !isset($args[1]) || !isset($args[2])) {
            $sender->sendMessage("Usage: /email (send mail) (subject) (message)");
        } else {
            // my email
            $my_email = $this->plugin->getConfig()->get("email");
            $my_password = $this->plugin->getConfig()->get("password");
            $my_host = $this->plugin->getConfig()->get("host");

            $mail = PHPLoader::getInstance();
            $host = $my_host;
            $email = $my_email;
            $password = $my_password;
            $port = $this->plugin->getConfig()->get("port");
            $ssl = $this->plugin->getConfig()->get("ssl");

            $send_email = $args[0] ?? "";
            $subject = $args[1] ?? "";
            $body = $args[2] ?? "";
            $path = $this->plugin->getDataFolder() . "index.html";
            $text = file_get_contents($path);
            $date = date("Y-m-d H:i:s");
            $text = str_replace("(content)", $body, $text);
            $text = str_replace("(email)", $email, $text);
            $text = str_replace("(time)", $date, $text);
            // html
            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->CharSet = 'UTF-8';
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $email;
                $mail->Password = $password;
                $mail->SMTPSecure = $ssl;
                $mail->Port = $port;
                $mail->addAddress($send_email, "User");
                $mail->setFrom($email, 'pmmp');

                $mail->isHTML();
                $mail->Subject = $subject;
                $mail->Body = $text;
                $mail->Timeout = 1;
                $mail->send();
                $sender->sendMessage("§aWe have successfully sent you an email.");
            } catch (\Exception $e) {
                $sender->sendMessage("§c". $e->getMessage());
                $this->plugin->getLogger()->error($e->getMessage());
            }
        }
    }
}