<?php

namespace Onfan\WSSEUserPasswordBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateHeaderCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('wsse:generate:header:userpassword')
             ->setDescription('Generate WSSE UserPassword authentication header')
             ->addArgument('username', InputArgument::REQUIRED, 'Username')
             ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestamp = (string)$now->format( 'Y-m-d\TH:i:s\Z' );
        $nonce = base64_encode(sha1(time() . 'salt' ));
        $secret = $password; // add password encryption here
        $digest = base64_encode(sha1(base64_decode($nonce) . $timestamp . $secret, true));
        
        $header = sprintf( 'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $username,
            $digest,
            $nonce,
            $timestamp
        );
        
        $output->writeln($header);
    }
}
