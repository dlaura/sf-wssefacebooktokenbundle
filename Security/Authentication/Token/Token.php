<?php

namespace Onfan\WSSEAccessTokenBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class Token extends AbstractToken
{
	public $created;
	public $accessToken;
	public $nonce;

	public function getCredentials()
	{
		return '';
	}
}