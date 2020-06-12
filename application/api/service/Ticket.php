<?php


namespace app\api\service;


use think\Exception;

class Ticket
{
    private $ticketUrl;
    const TICKET_CACHED_KEY = 'ticket';
    const TICKET_EXPIRE_IN = 7000;

    function __construct()
    {
        $tokenAccess = new AccessToken();
        $token = $tokenAccess->get();
        if(!$token) {
            throw new Exception('token获取失败');
        }
        $url = config('wx.ticket_url');
        $url = sprintf($url, $token);
        $this->ticketUrl = $url;
        var_dump($this->ticketUrl);
    }

    public function get()
    {
        $token = $this->getFromCache();
        if(!$token){
            return $this->getFromWxServer();
        }
        else{
            return $token['ticket'];
        }
    }

    private function getFromCache(){
        $token = cache(self::TICKET_CACHED_KEY);
        if(!$token){
            return null;
        }
        return $token;
    }


    private function getFromWxServer()
    {
        $ticket = curl_get($this->ticketUrl);
        $ticket = json_decode($ticket, true);
        if (!$ticket)
        {
            throw new Exception('获取ticket异常');
        }
        if(!isset($ticket['errcode']) || $ticket['errcode'] != 0){
            throw new Exception($ticket['errmsg']);
        }
        $this->saveToCache($ticket);
        return $ticket['ticket'];
    }

    private function saveToCache($ticket){
        cache(self::TICKET_CACHED_KEY, $ticket, self::TICKET_EXPIRE_IN);
    }


}