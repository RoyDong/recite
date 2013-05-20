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
use Recite\DataBundle\Entity\ZiEn;
use Recite\DataBundle\Entity\ZiExample;

class CrawlDictcnCommand extends ContainerAwareCommand
{
    private $output;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('data:crawl:dictcn')
            ->setDescription('craw data from dict.cn')
        ;
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        $counter = 0;
        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $zis = $this->em
                ->createQuery('SELECT z FROM ReciteDataBundle:Zi z')
                ->getResult();

        foreach($zis as $zi){
            $this->output->writeln('Crawling '.$zi->getChar());
            $html = $this->call('http://dict.cn/'.$zi->getChar());
            $basicTrans = $this->getBasicTrans($html);

            if($basicTrans){
                foreach($basicTrans as $content){
                    $en = (new ZiEn)->setZi($zi)->setContent($content);
                    $this->em->persist($en);
                    $counter++;
                }
            }

            $examples = $this->getExamples($html);

            if($examples){
                foreach($examples as $v){
                    $example = (new ZiExample)->setZi($zi)
                            ->setContent($v[0])
                            ->setEn($v[1]);

                    $this->em->persist($example);
                    $counter++;
                }
            }

            if($counter >= 500){
                $this->em->flush();
                //$this->em->clear();
                $counter = 0;
            }
        }
    }

    private function call($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: dw beta 0.0.1']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    /**
     * 
     * 基本释义
     */
    private function getBasicTrans($html){
        preg_match('/<div class="layout cn">(.*?)<\/div>/is', $html, $matches);

        if(isset($matches[1])){
            $ul = preg_replace('/[\r\n\t]/', '', $matches[1]);
            preg_match_all('/<a href="[^>]+">(.*?)<\/a>/i', $ul, $matches);

            if(isset($matches[1])){
                return $matches[1];
            }
        }

        return false;
    }

    private function getExamples($html){
        preg_match('/<div class="layout sort">(.*?)<\/div>/is', $html, $matches);

        if(isset($matches[1])){
            $ul = preg_replace('/[\r\n\t]/', '', $matches[1]);
            preg_match_all('/<li>(.*?)<\/li>/i', $ul, $matches);
            $examples = [];

            if(isset($matches[1])){
                foreach($matches[1] as $m){
                    $example = explode('<br/>', 
                            preg_replace('/<\/?em[^>]*>/i', '', $m));

                    if(empty($example[0]) || empty($example[1])){
                        continue;
                    }

                    $examples[] = $example;
                }

                return $examples;
            }
        }

        return false;
    }
}