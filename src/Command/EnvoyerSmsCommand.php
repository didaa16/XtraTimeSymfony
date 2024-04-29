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
    // Récupérer tous les abonnements depuis la base de données
    $abonnements = $this->abonnementRepository->findAll();

    // Configurer les identifiants Twilio
    $twilioAccountSid = 'ACc54419fc41b7438b7f6b18e46ac7d9a6';
    $twilioAuthToken = '8186bc93d690248fbe1c258c37f830b4';
    $twilioNumber = '+16812026259';

    // Initialiser le client Twilio
    $twilio = new Client($twilioAccountSid, $twilioAuthToken);

    foreach ($abonnements as $abonnement) {
        // Récupérer la date de fin de l'abonnement
        $dateFin = $abonnement->getDateFin();
        $dateDebut=$abonnement->getDate();

        // Récupérer la date actuelle et la date dans deux jours
        $dateActuelle = new \DateTime();
        $dateDeuxJoursPlusTard = clone $dateFin;
        $dateDeuxJoursPlusTard->sub(new \DateInterval('P2D'));
        $output->writeln('Date de debut de l\'abonnement: ' . $dateDebut->format('Y-m-d'));
        $output->writeln('Date de fin de l\'abonnement: ' . $dateFin->format('Y-m-d'));
$output->writeln('Date actuelle: ' . $dateActuelle->format('Y-m-d'));
$output->writeln('Date dans deux jours: ' . $dateDeuxJoursPlusTard->format('Y-m-d'));

if ($dateActuelle->format('d') == $dateDeuxJoursPlusTard->format('d') &&
    $dateActuelle->format('m') == $dateDeuxJoursPlusTard->format('m') &&
    $dateActuelle->format('Y') == $dateDeuxJoursPlusTard->format('Y')) 
 {
    try {
        // Récupérer le numéro de téléphone de l'abonné
        $numTel = "+21652354494";

        // Envoyer le SMS avec Twilio
        $twilio->messages->create(
            $numTel,
            ['from' => $twilioNumber, 'body' => 'Votre abonnement se termine bientôt. Renouvelez-le dès maintenant !']
        );

        // Afficher un message de succès
        $output->writeln('SMS envoyé à ' . $numTel);
    } catch (\Exception $e) {
        // Afficher un message d'erreur en cas d'échec de l'envoi du SMS
        $output->writeln('Erreur lors de l\'envoi du SMS à ' . $numTel . ': ' . $e->getMessage());
    }
}
}

// Retourner le statut de réussite de la commande
return Command::SUCCESS;
}

    
}
