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
use Recite\DataBundle\Entity\Zi;
use Recite\DataBundle\Entity\Book;

class GenerateBookCommand extends ContainerAwareCommand
{
    private $output;

    private $chars;

    /**
     *
     * @var Book
     */
    private $book;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('book:generate')
            ->setDescription('generate a book')
            ->addArgument('title', InputArgument::REQUIRED, 'title of the book')
            ->addArgument('file', InputArgument::REQUIRED, 'full path to the file of zi')
            ->addOption('level', 'l', InputOption::VALUE_OPTIONAL, 'level of the book')
        ;
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        $title = $input->getArgument('title');
        $file = $input->getArgument('file');
        $level = $input->getOption('level') ?: 0;
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $ziRepo = $em->getRepository('ReciteDataBundle:Zi');
        $book = (new Book)->setTitle($title)->setLevel($level);
        $em->persist($book);

        if(file_exists($file)){
            $chars = array_unique(preg_split('/[\s\w.-_|]+/', file_get_contents($file)));
            $count = 0;

            foreach($chars as $char){
                $char = trim($char);

                if($char){
                    $zi = $ziRepo->findOneByChar($char);

                    if($zi){
                        $count++;
                        $book->addZi($zi);
                    }else{
                        $output->writeln("Can't found $char in database");
                    }
                }
            }

            $book->setZiCount($count);
            $em->flush();

            $result = <<<STR
All done
Info:
    id: {$book->getId()}
    Title: $title
    Description: 
    Level: $level
    Zi count: $count
STR;
            $output->writeln($result);

        }else
            throw new \ErrorException('file not found');
    }
}