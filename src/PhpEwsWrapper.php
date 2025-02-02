<?php
/**
 * @author Amir Sanni <amirsanni@gmail.com>
 */

namespace amirsanni\phpewswrapper;

use jamesiarmes\PhpEws\Client;
use amirsanni\phpewswrapper\Tasks;
use amirsanni\phpewswrapper\Folders;
use amirsanni\phpewswrapper\Contacts;
use amirsanni\phpewswrapper\Messages\Mail;
use amirsanni\phpewswrapper\Calendar\Events;
use Exception;

class PhpEwsWrapper {
    protected $ews;//ews connection client
    protected $version;
    
    /** MESSAGES */
    protected $messages_class_obj;
    public $limit;

    public $events;
    public $mail;
    public $contacts;
    public $tasks;
    public $folders;

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function __construct(string $access_token, string $email, string $password = "", string $server="outlook.office365.com", $version='2016'){
        try{
            $this->__setVersion($version);
            $this->ews = new Client($server, $this->version);

            if($access_token) {
                $this->ews->authWithOauth2($access_token);
            } else if($email && $password){
                $this->ews->authWithUserAndPass($email, $password);
            } else {
                throw new Exception("Authentication failed. Provide either your access token or your email and password");
            }

            //instantiate required classes
            $this->events = new Events($this->ews);
            $this->mail = new Mail($this->ews, $email);
            $this->contacts = new Contacts($this->ews);
            $this->tasks = new Tasks($this->ews);
            $this->folders = new Folders($this->ews);
        }

        catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    private function __setVersion($version){
        switch($version){
            case 2007:
                $this->version = Client::VERSION_2007;
                break;

            case 2009:
                $this->version = Client::VERSION_2009;
                break;

            case 2010:
                $this->version = Client::VERSION_2010;
                break;

            case 2013:
                $this->version = Client::VERSION_2013;
                break;

            default:
                $this->version = Client::VERSION_2016;
        }
    }
}
