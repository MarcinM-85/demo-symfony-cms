<?php

namespace App\Bundle\FileGator\Service\Session;

use Filegator\Kernel\Request;
use Filegator\Services\Service;
use Filegator\Services\Session\SessionStorageInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionStorage implements Service, SessionStorageInterface
{
    protected $request;

    protected $config;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function init(array $config = [])
    {
        // we don't have a previous session attached
        if (! $this->getSession()) {
            $handler = $config['handler'];
            $session = new Session($handler());
            $session->setName('filegator');

            $this->setSession($session);
        }
    }

    public function save()
    {
        $this->getSession()->save();
    }

    public function set(string $key, $data)
    {
        return $this->getSession()->set($key, $data);
    }

    public function get(string $key, $default = null)
    {
        return $this->getSession() ? $this->getSession()->get($key, $default) : $default;
    }

    public function invalidate()
    {
        if (! $this->getSession()->isStarted()) {
            $this->getSession()->start();
        }

        $this->getSession()->invalidate();
    }

    private function setSession(Session $session)
    {
        return $this->request->setSession($session);
    }

    private function getSession(): ?Session
    {
        return $this->request->getSession();
    }
}
