<?php

namespace App\Controller;

use App\Document\Charge;
use App\Document\Item;
use App\Form\ChargeForm;
use App\Repository\ChargeRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;



class ChargeController extends AbstractController{
    #[Route('/charges', name: 'app_charges_list')]
    public function index(DocumentManager $dm, Security $security): Response{
        // $charges = $chargeRepository->findAll();
        $user = $security->getUser();
        $charges = $dm->getRepository(Charge::class)->findBy(['owner' => $user]);

        return $this->render('charge/index.html.twig', [
            'charges' => $charges,
        ]);
    }


    #[Route('/charges/new', name: 'app_charges_new')]
    public function new(Request $request, DocumentManager $dm, Security $security): Response{
        $user = $security->getUser();
        $charge = new Charge();
        $charge->addItem(new Item());
        $charge->setOwner($user); 
    
        $form = $this->createForm(ChargeForm::class, $charge);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $valorTotal = $form->get('amount')->getData();
 
            $parcelasChoices = [];
            for ($i = 1; $i <= 6; $i++) {
                $valorParcela = number_format($valorTotal / $i, 2, ',', '.');
                $parcelasChoices["{$i}x de R$ {$valorParcela}"] = $i;
            }

            $form = $this->createForm(ChargeForm::class, $charge, [  'parcelas_choices' => $parcelasChoices  ]);
            $form->handleRequest($request); 

            if ($form->isValid()) {
                $charge = $form->getData();
                $charge->setCreatedAt(new \DateTimeImmutable());
                $dm->persist($charge);
                $dm->flush();

                $this->addFlash('success', 'Cobrança criada com sucesso!');
                return $this->redirectToRoute('app_charges_show', ['id' => $charge->getId()]);
            }
        }

        return $this->render('charge/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/charges/{id}', name: 'app_charges_show')]
    public function show(string $id, ChargeRepository $chargeRepository, Security $security): Response{
        $charge = $chargeRepository->find($id);

        if ($charge->getOwner() !== $security->getUser()) {
            throw $this->createAccessDeniedException('Você não tem permissão para acessar essa cobrança.');
        }        

        if (!$charge) {
            throw $this->createNotFoundException('Cobrança não encontrada.');
        }

        return $this->render('charge/show.html.twig', [
            'charge' => $charge,
        ]);
    }

    #[Route('/charges/{id}/edit', name: 'app_charges_edit')]
    public function edit(string $id, Request $request, DocumentManager $dm, ChargeRepository $chargeRepository, Security $security): Response {
        $charge = $chargeRepository->find($id);
        $user = $security->getUser();
        $charge->setOwner($user);

        if ($charge->getOwner() !== $security->getUser()) {
            throw $this->createAccessDeniedException('Você não tem permissão para acessar essa cobrança.');
        }
        
        if (!$charge) {
            throw $this->createNotFoundException('Cobrança não encontrada.');
        }

        foreach ($charge->getItems() as $item) {
            if (empty($item->getId())) {
                $item->setId((string)new \MongoDB\BSON\ObjectID());
            }
        }

        $form = $this->createForm(ChargeForm::class, $charge);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $valorTotal = $form->get('amount')->getData();

            $parcelasChoices = [];
            for ($i = 1; $i <= 6; $i++) {
                $valorParcela = number_format($valorTotal / $i, 2, ',', '.');
                $parcelasChoices["{$i}x de R$ {$valorParcela}"] = $i;
            }

            $form = $this->createForm(ChargeForm::class, $charge, [
                'parcelas_choices' => $parcelasChoices
            ]);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $charge = $form->getData();
                $charge->setCreatedAt(new \DateTimeImmutable());
                $dm->flush();

                $this->addFlash('success', 'Cobrança atualizada com sucesso!');
                return $this->redirectToRoute('app_charges_show', ['id' => $charge->getId()]);
            }
        }

        return $this->render('charge/edit.html.twig', [
            'charge' => $charge,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/charges/{id}/delete', name: 'app_charges_delete', methods: ['POST'])]
    public function delete(string $id, Request $request, DocumentManager $dm, ChargeRepository $chargeRepository, CsrfTokenManagerInterface $csrfTokenManager, Security $security): Response{
        $charge = $chargeRepository->find($id);

        if ($charge->getOwner() !== $security->getUser()) {
            throw $this->createAccessDeniedException('Você não tem permissão para acessar essa cobrança.');
        }
        
        if (!$charge) {
            throw $this->createNotFoundException('Cobrança não encontrada');
        }

        $token = new CsrfToken('delete' . $charge->getId(), $request->get('_token'));
        if(!$csrfTokenManager->isTokenValid($token)){
            $this->addFlash('error', 'Token CSRF inválido. Tente Novamente.');
            return $this->redirectToRoute('app_charges_show', ['id' => $charge->getId()]);
        }

        $dm->remove($charge);
        $dm->flush();

        $this->addFlash('success', 'Cobrança excluída com sucesso!');
        return $this->redirectToRoute('app_charges_list');
    }

    private function createInstallments(Charge $charge, int $installmentsCount): void{
    $installmentValue = $charge->getAmount() / $installmentsCount;
    
    for ($i = 1; $i <= $installmentsCount; $i++) {
        $dueDate = (new \DateTimeImmutable())->add(new \DateInterval("P{$i}M"));
        
        $charge->addInstallment([
            'number' => $i,
            'due_date' => $dueDate,
            'amount' => $installmentValue,
            'status' => 'PENDENTE'
        ]);
    }
}

    private function calculateInstallments(float $amount): array{
        $installments = [];
        $maxInstallments = $this->getMaxInstallments($amount);
        $installmentValue = $amount / $maxInstallments;

        for ($i = 1; $i <= $maxInstallments; $i++) {
            $dueDate = (new \DateTimeImmutable())->add(new \DateInterval("P{$i}M"));
            $installments[] = [
                'number' => $i,
                'due_date' => $dueDate,
                'amount' => $installmentValue,
                'status' => 'pending'
            ];
        }
        return $installments;
    }

    private function getMaxInstallments(float $amount): int{
        if ($amount >= 1000) return 10;
        if ($amount >= 500) return 6;
        if ($amount >= 200) return 5;
        return 1;
    }
}