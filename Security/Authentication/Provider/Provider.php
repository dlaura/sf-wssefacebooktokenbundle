<?php

namespace Onfan\WSSEAccessTokenBundle\Security\Authentication\Provider;

use Onfan\WSSEAccessTokenBundle\Security\Authentication\Token\Token;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Provider implements AuthenticationProviderInterface
{
	private $userProvider;
	private $nonceDir;
	private $lifetime;

	public function __construct(UserProviderInterface $userProvider, $nonceDir=null, $lifetime=300)
	{
		$this->userProvider = $userProvider;
		$this->nonceDir = $nonceDir;
		$this->lifetime = $lifetime;
	}

	public function authenticate(TokenInterface $token)
	{
		$user = $this->userProvider->loadUserByUsername($token->getUsername());
                
		if($user && $this->validateAccessToken($token->accessToken, $token->nonce, $token->created, $user))
		{
			$authenticatedToken = new Token($user->getRoles());
			$authenticatedToken->setUser($user);
                        $authenticatedToken->setAuthenticated(true);
                        
			return $authenticatedToken;
		}

		throw new AuthenticationException('WSSE AccessToken authentication failed.');
	}

	protected function validateAccessToken($accessToken, $nonce, $created, $user)
	{
		//expire timestamp after specified lifetime
		if(time() - strtotime($created) > $this->lifetime)
		{
                    return false;
		}

		if($this->nonceDir)
		{
			//validate nonce is unique within specified lifetime
			if(file_exists($this->nonceDir.'/'.$nonce) && file_get_contents($this->nonceDir.'/'.$nonce) + $this->lifetime < time())
			{
				throw new NonceExpiredException('Previously used nonce detected');
			}

			file_put_contents($this->nonceDir.'/'.$nonce, time());
		}

		
		//validate access token
                foreach ($user->getAccessTokens() as $token) {
                    if ($token->getAccessToken() === base64_decode($accessToken)) {
                        if ($token->getEnabled()) {
                            // TODO: check token expiration ...
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
                
		return false;
	}

	public function supports(TokenInterface $token)
	{
		return $token instanceof Token;
	}
}
