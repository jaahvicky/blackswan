<?php

/*
 * Contact Creation
 */

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ContactCreatedEvent extends Event
{
    protected $contact;

    public function __construct(User $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): User
    {
        return $this->contact;
    }
}