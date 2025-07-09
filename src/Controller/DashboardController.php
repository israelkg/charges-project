<?php
namespace App\Controller;

use App\Document\Charge;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController{

    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DocumentManager $dm): Response{
        $user = $this->getUser();
        $userId = $user->getId(); 
        $cobrancas = $dm->getRepository(Charge::class)->findBy(['userId' => $userId]);

        return $this->render('dashboard/index.html.twig', [
            'usuario' => $user,
            'cobrancas' => $cobrancas
        ]);
    }
}


// $user = $this->getUser();
// $userId = $user->getId(); // ou getUserIdentifier()

// // Exemplo com Doctrine ODM (MongoDB)
// $cobrancas = $cobrancaRepository->findBy(['userId' => $userId]);