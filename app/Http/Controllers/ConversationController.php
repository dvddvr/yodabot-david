<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConversationController extends Controller
{
    /**
     * Show the conversation page
     *
     */
    public function index()
    {
        $now = new DateTime();

        if (!(Session::has('chatbotApiUrl')) || $now->getTimestamp() >= session('expiration')) {
            $this->getAuthorization();
            $this->getSessionToken();
        }

        return view('conversation');
    }

    /**
     * API Authorization (Get AcesssToken and Get API URL)
     *
     */
    public function getAuthorization()
    {
        $req = new Client();

        $headers = [
            'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Content-Type' => 'application/json'
        ];
        $body = [
            'secret' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg'
        ];
        $response = $req->post('https://api.inbenta.io/v1/auth', [
            'headers' => $headers,
            'body' => json_encode($body)
        ]);
        $response = json_decode($response->getBody());
        session(['apiAccessToken' => 'Bearer ' . $response->accessToken]);
        session(['expiration' => $response->expiration]);

        $headers = [
            'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Authorization' => session('apiAccessToken')
        ];
        $response = $req->get('https://api.inbenta.io/v1/apis', [
            'headers' => $headers
        ]);
        $response = json_decode($response->getBody());
        session(['chatbotApiUrl' => $response->apis->chatbot . '/v1/conversation']);
    }

    /**
     * Get Session Token and Show the conversation page
     *
     */
    public function getSessionToken()
    {
        $req = new Client();
        $headers = [
            'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Authorization' => session('apiAccessToken')
        ];

        $payload = [
            'timezone' => 'Europe/Madrid'
        ];
        $response = $req->post(session('chatbotApiUrl'), [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);
        $response = json_decode($response->getBody());

        session(['apiSessionToken' => 'Bearer ' . $response->sessionToken]);
    }

    /**
     * Get History Conversation
     *
     */
    public function getHistory() {
        $req = new Client();

        $headers = [
            'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Authorization' => session('apiAccessToken'),
            'x-inbenta-session' => session('apiSessionToken')
        ];
            
        $response = $req->get(session('chatbotApiUrl') . '/history', [
            'headers' => $headers,
        ]);

        return $response->getBody();
    }

    /**
     * Send message to Yoda
     *
     */
    public function sendMessage(String $message) {
        $now = new DateTime();

        //Si ha caducado el AccessToken
        if ($now->getTimestamp() >= session('expiration')) { 
            $this->getAuthorization();
            $this->getSessionToken();
        }

        $req = new Client();
        $headers = [
            'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Authorization' => session('apiAccessToken'),
            'x-inbenta-session' => session('apiSessionToken')
        ];
        $payload = [
            'message' => $message,
        ];
        $response = $req->post(session('chatbotApiUrl') . '/message', [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return $response->getBody();
    }

    /**
     * Get list from GraphQL API
     *
     */
    public function getList(String $petition) {
        switch ($petition) {
            case 'characters':
                $body = '{"query":"{allPeople{people{name}}}"}';
                break;
            default:
               $body = '{"query":"{allFilms{films{title}}}"}';
        }

        $req = new Client();
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $response = $req->post('https://inbenta-graphql-swapi-prod.herokuapp.com/api', [
            'headers' => $headers,
            'body' => $body
        ]);
        return $response->getBody();
    }
}