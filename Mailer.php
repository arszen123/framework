<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 4/7/18
 * Time: 10:52 AM
 */

namespace Framework;


class Mailer
{

    private $to;
    private $subject;
    private $message;
    private $headers;
    protected $templateName;
    private $model;

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setHeaders($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function send()
    {
        $this->renderBody();
        return mail($this->to, $this->subject, $this->message, $this->headers);
    }

    public function initHeaders()
    {
        $this->headers = array(
            'From' => 'aliarszen@gmail.com',
            'Reply-To' => 'webmaster@example.com',
            'X-Mailer' => 'PHP/' . phpversion()
        );
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return ['model' => $this->model];
    }

    public function renderBody()
    {
        if (isset($this->templateName)) {
            $this->message = View::renderHTML(WEBAPP_ROOT . '/View/Mail/' . $this->templateName,
                $this->getModel());
        }
    }

}
