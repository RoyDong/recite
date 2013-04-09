<?php

/*
 * crawl data
 */
namespace Recite\DataBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends ContainerAwareCommand
{
    private $output;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('data:crawl')
            ->setDescription('craw data')
        ;
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $zis = $this->em
                ->createQuery('SELECT z.id,z.char FROM ReciteDataBundle:Zi z')
                ->getResult();

        foreach($zis as $zi){
            $result = $this->call('http://dict.cn/'.$zi['char']);
        }
    }

    private function call($url){
        $this->output->writeln('Crawling page: '.$url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: QM beta 0.0.1']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);
        $this->output->writeln('done');

        echo $html;
    }
}