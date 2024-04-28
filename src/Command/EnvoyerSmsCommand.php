<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twilio\Rest\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AbonnementRepository;

class EnvoyerSmsCommand extends Command
{
    private $entityManager;
    private $abonnementRepository;

    public function __construct(EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository)
    {
        $this->entityManager = $entityManager;
        $this->abonnementRepository = $abonnementRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:envoyer-sms')
            ->setDescription('Envoyer un SMS aux abonnés dont l\'abonnement expire dans deux jours');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $abonnements = $this->abonnementRepository->findAbonnementsExpireDansDeuxJours();
        $twilioAccountSid = 'AC294826a0d7e01332b57990ad5f8149d6';
        $twilioAuthToken =  'ac5f577e5b9b37e9e6114295d4cab892';
        $twilioNumber = '+13392290039';
    
        $twilio = new Client($twilioAccountSid, $twilioAuthToken);
    
        foreach ($abonnements as $abonnement) {
            // Vérifier si un SMS a déjà été envoyé pour cet abonnement
            if (!$abonnement->getSmsEnvoye()) {
                $dateFin = $abonnement->getDateFin();
                $dateActuelle = new \DateTime();
                $dateActuelle->add(new \DateInterval('P2D'));
                $numTel = $abonnement->getNumTel();
    
                // Si la date actuelle est avant deux jours de la date de fin de l'abonnement
                if ($dateActuelle < $dateFin) {
                    try {
                        $twilio->messages->create(
                            $numTel,
                            ['from' => $twilioNumber, 'body' => 'Votre abonnement se termine bientôt. Renouvelez-le dès maintenant !']
                        );
                        $output->writeln('SMS envoyé à ' . $numTel);
    
                        // Marquer l'abonnement comme ayant eu un SMS envoyé
                        $abonnement->setSmsEnvoye(true);
                        $this->entityManager->persist($abonnement);
                        $this->entityManager->flush();
                    } catch (\Exception $e) {
                        $output->writeln('Erreur lors de l\'envoi du SMS à ' . $numTel . ': ' . $e->getMessage());
                    }
                }
            }
        }
    
        return Command::SUCCESS;
    }
    

    
}
