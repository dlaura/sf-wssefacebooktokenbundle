<?php

namespace Onfan\WSSEAccessTokenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateHeaderCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('wsse:generate:header:accesstoken')
             ->setDescription('Generate WSSE AccessToken authentication header')
             ->addArgument('username', InputArgument::REQUIRED, 'Username')
             ->addArgument('accesstoken', InputArgument::REQUIRED, 'Access Token');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $username = $input->getArgument('username');
        $accessToken = $input->getArgument('accesstoken');
        
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestamp = (string)$now->format( 'Y-m-d\TH:i:s\Z' );
        $nonce = base64_encode(sha1(time() . 'salt' ));
        $token = base64_encode($accessToken);
        
        $header = sprintf( 'X-WSSE: AccessToken Username="%s", AccessToken="%s", Nonce="%s", Created="%s"',
            $username,
            $token,
            $nonce,
            $timestamp
        );
        
        $output->writeln($header);
    }
}
