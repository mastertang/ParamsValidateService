<?php

namespace ParamsValidateMicroServices\tool;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class MailerTool
 * @package ParamsValidateMicroServices\tool
 */
class MailerTool
{
    /**
     * @var string 服务器地址
     */
    protected $host = "";

    /**
     * @var int 端口
     */
    protected $port = 587;

    /**
     * @var bool 是否验证
     */
    protected $auth = false;

    /**
     * @var string 用户名
     */
    protected $username = "";

    /**
     * @var string 用户密码
     */
    protected $password = "";

    /**
     * @var string TLS encryption
     */
    protected $secure = "tls";

    /**
     * @var array 发送邮箱地址
     */
    protected $emailFrom = [];

    /**
     * @var array 接收邮箱地址
     */
    protected $receiveAddress = [];

    /**
     * @var array 接收replyto邮箱地址
     */
    protected $replytoAddress = [];

    /**
     * @var array 接收cc邮箱地址
     */
    protected $ccEmailAddress = [];

    /**
     * @var array 接收bcc邮箱地址
     */
    protected $bccEmailAddress = [];

    /**
     * @var array 附件
     */
    protected $attachment = [];

    /**
     * @var string 邮件标题
     */
    protected $subjcet = "";

    /**
     * @var string 内容
     */
    protected $body = "";

    /**
     * @var string 子内容
     */
    protected $altBody = "";

    /**
     * 设置基本配置
     *
     * @param $host
     * @param int $port
     * @param bool $auth
     * @param string $username
     * @param string $password
     * @param string $secure
     */
    public function setBaseConfig(
        $host,
        $port = 587,
        $auth = false,
        $username = "",
        $password = "",
        $secure = "tls"
    )
    {
        $this->host     = empty($host) ? "" : $host;
        $this->port     = is_numeric($port) ? (int)$port : 587;
        $this->auth     = is_bool($auth) ? $auth : false;
        $this->username = empty($username) ? "" : false;
        $this->password = empty($password) ? "" : false;
    }

    /**
     * 设置发送的邮箱地址
     *
     * @param $email
     * @param string $name
     */
    public function setMailerFrom($email, $name = "")
    {
        if (StringTool::isEmailAddress($email)) {
            $this->emailFrom = [$email, $name];
        }
    }

    /**
     * 总设置邮箱地址
     *
     * @param null $receiveAddress
     * @param null $replyToAddress
     * @param null $ccAddress
     * @param null $bccAddress
     */
    public function setAllMailReceviceAddress(
        $receiveAddress = null,
        $replyToAddress = null,
        $ccAddress = null,
        $bccAddress = null
    )
    {
        $this->setMailReceiveAddress($receiveAddress);
        $this->setMailReplyToAddress($replyToAddress);
        $this->setMailCCAddress($ccAddress);
        $this->setMailBCCAddress($bccAddress);
    }

    /**
     * 设置接收邮箱地址
     *
     * @param $emailArray
     * [
     *   [email,name],
     *   [email]
     *   email
     * ]
     */
    public function setMailReceiveAddress($emailArray)
    {
        if (is_array($emailArray)) {
            $newEmailArray = [];
            foreach ($emailArray as $emailItem) {
                if (is_array($emailItem) && isset($emailItem[0]) && StringTool::isEmailAddress($emailItem[0])) {
                    $newEmailArray[] = [
                        $emailItem[0],
                        isset($emailItem[1]) && is_string($emailItem[1]) ? $emailItem[1] : ""
                    ];
                } else if (is_string($emailItem) && StringTool::isEmailAddress($emailItem)) {
                    $newEmailArray[] = [$emailItem, ""];
                }
            }
            $this->receiveAddress = $newEmailArray;
        }
    }

    /**
     * 设置replyto接收邮箱地址
     *
     * @param $emailArray
     * [
     *   [email,name],
     *   [email]
     *   email
     * ]
     */
    public function setMailReplyToAddress($emailArray)
    {
        if (is_array($emailArray)) {
            $newEmailArray = [];
            foreach ($emailArray as $emailItem) {
                if (is_array($emailItem) && isset($emailItem[0]) && StringTool::isEmailAddress($emailItem[0])) {
                    $newEmailArray[] = [
                        $emailItem[0],
                        isset($emailItem[1]) && is_string($emailItem[1]) ? $emailItem[1] : ""
                    ];
                } else if (is_string($emailItem) && StringTool::isEmailAddress($emailItem)) {
                    $newEmailArray[] = [$emailItem, ""];
                }
            }
            $this->replytoAddress = $newEmailArray;
        }
    }

    /**
     * 设置cc接收邮箱地址
     *
     * @param $emailArray
     * [
     *   [email,name],
     *   [email]
     *   email
     * ]
     */
    public function setMailCCAddress($emailArray)
    {
        if (is_array($emailArray)) {
            $newEmailArray = [];
            foreach ($emailArray as $emailItem) {
                if (is_array($emailItem) && isset($emailItem[0]) && StringTool::isEmailAddress($emailItem[0])) {
                    $newEmailArray[] = [
                        $emailItem[0],
                        isset($emailItem[1]) && is_string($emailItem[1]) ? $emailItem[1] : ""
                    ];
                } else if (is_string($emailItem) && StringTool::isEmailAddress($emailItem)) {
                    $newEmailArray[] = [$emailItem, ""];
                }
            }
            $this->ccEmailAddress = $newEmailArray;
        }
    }


    /**
     * 设置bcc接收邮箱地址
     *
     * @param $emailArray
     * [
     *   [email,name],
     *   [email]
     *   email
     * ]
     */
    public function setMailBCCAddress($emailArray)
    {
        if (is_array($emailArray)) {
            $newEmailArray = [];
            foreach ($emailArray as $emailItem) {
                if (is_array($emailItem) && isset($emailItem[0]) && StringTool::isEmailAddress($emailItem[0])) {
                    $newEmailArray[] = [
                        $emailItem[0],
                        isset($emailItem[1]) && is_string($emailItem[1]) ? $emailItem[1] : ""
                    ];
                } else if (is_string($emailItem) && StringTool::isEmailAddress($emailItem)) {
                    $newEmailArray[] = [$emailItem, ""];
                }
            }
            $this->bccEmailAddress = $newEmailArray;
        }
    }

    /**
     * 设置附件
     *
     * @param $attachment
     * [
     *   [email,name],
     *   [email]
     *   email
     * ]
     */
    public function setMailAttachment($attachment)
    {
        if (is_array($attachment)) {
            $mewAttachmentArray = [];
            foreach ($attachment as $attachmentItem) {
                if (is_array($attachmentItem) && isset($attachmentItem[0]) && is_file($attachmentItem[0])) {
                    $mewAttachmentArray[] = [
                        $attachmentItem[0],
                        isset($attachmentItem[1]) && is_string($attachmentItem[1]) ? $attachmentItem[1] : ""
                    ];
                } else if (is_string($attachmentItem) && is_file($attachmentItem)) {
                    $mewAttachmentArray[] = [$attachmentItem, ""];
                }
            }
            $this->attachment = $mewAttachmentArray;
        }
    }

    /**
     * 设置标题
     *
     * @param $subject
     */
    public function setSubject($subject)
    {
        if (is_string($subject)) {
            $this->subjcet = $subject;
        }
    }

    /**
     * 设置内容
     *
     * @param $body
     */
    public function setBody($body)
    {
        if (is_string($body)) {
            $this->body = $body;
        }
    }

    /**
     * 设置子内容
     *
     * @param $altbody
     */
    public function setAltbody($altbody)
    {
        if (is_string($altbody)) {
            $this->altBody = $altbody;
        }
    }

    /**
     * 发送邮件
     */
    public function send($html = false)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host     = $this->host;
            $mail->SMTPAuth = $this->auth;
            if ($this->auth) {
                $mail->Username = $this->username;
                $mail->Password = $this->password;
            }

            $mail->SMTPSecure = in_array(strtolower($this->secure), ['tls', 'ssl']) ? strtolower($this->secure) : 'tls';
            $mail->Port       = $this->port;

            $mail->setFrom($this->emailFrom[0], $this->emailFrom[1]);
            foreach ($this->receiveAddress as $address) {
                $mail->addAddress($address[0], $address[1]);
            }
            foreach ($this->replytoAddress as $address) {
                $mail->addReplyTo($address[0], $address[1]);
            }
            foreach ($this->ccEmailAddress as $address) {
                $mail->addCC($address[0], $address[1]);
            }
            foreach ($this->bccEmailAddress as $address) {
                $mail->addBCC($address[0], $address[1]);
            }
            foreach ($this->attachment as $item) {
                $mail->addAttachment($item[0], $item[1]);
            }
            $mail->isHTML($html);
            $mail->Subject = $this->subjcet;
            $mail->Body    = $this->body;
            $mail->AltBody = $this->altBody;

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}