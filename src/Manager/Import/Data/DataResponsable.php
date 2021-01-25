<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataResponsable extends DataSanitize implements Data
{
    protected $contact;
    protected $tel;
    protected $email;
    protected $codeNego;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->contact              = $data[105];
            $this->tel                  = $data[104];
            $this->email                = $data[106];
            $this->codeNego             = $data[122];
        }elseif($type == 1){
            $this->contact              = $data->CONTACT;
            $this->tel                  = $data->INFO_CONTACT;
            $this->email                = $data->MAIL_AGENCE;
            $this->codeNego             = $data->NEGOCIATEUR;
        }else{
            $phone = str_replace('+33', '0', $data['user']['phone']);
            $mobile = str_replace('+33', '0', $data['user']['mobile']);
            
            $this->contact              = ucfirst($data['user']['firstname']) . " " . strtoupper($data['user']['lastname']);
            $this->tel                  = $phone ? $phone : ($mobile ? $mobile : '');;
            $this->email                = $data['user']['email'] ? $data['user']['email'] : null;
            $this->codeNego             = null;
        }
        $this->setTel($this->formattedTel($this->tel));
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCodeNego()
    {
        return $this->codeNego;
    }

    /**
     * @param mixed $codeNego
     */
    public function setCodeNego($codeNego): void
    {
        $this->codeNego = $codeNego;
    }

}
