<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Document\Charge;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExportController extends AbstractController{
    #[Route('/charges/export/csv', name: 'app_charges_export_csv')]
    public function exportCsv(ManagerRegistry $doctrine, Security $security): Response{
        $user = $security->getUser();
        $charges = $doctrine->getRepository(Charge::class)->findBy(['user' => $user]);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($doctrine) {
            $repository = $doctrine->getRepository(Charge::class);
            $charges = $repository->findAll();

            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Cliente', 'Email', 'Valor', 'Vencimento', 'Telefone', 'Status']);

            foreach ($charges as $charge) {
                fputcsv($handle, [
                    $charge->getId(),
                    $charge->getCustomerName(),
                    $charge->getCustomerEmail(),
                    number_format($charge->getAmount(), 2, ',', '.'), // opcional: R$ formatado
                    $charge->getDueDate()?->format('d/m/Y'),
                    $charge->getNumberPhone(),
                    $charge->getStatus(),
                ]);
            }

            fclose($handle);
        });

        $filename = 'cobrancas_' . date('Ymd_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    #[Route('/charge/{id}/export/pdf', name: 'app_charge_export_pdf')]
    public function exportPdf(Charge $charge): Response{
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $html = $this->renderView('pdfs/list_pdf.html.twig', [
            'charge' => $charge,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();


        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cobranca_' . $charge->getId() . '.pdf"',
        ]);
    }
    #[Route('/dashboard/export/pdf', name: 'app_dashboard_export_pdf')]
    public function exportDashboardPdf(ManagerRegistry $doctrine, Security $security): Response{
        $user = $security->getUser();
        $charges = $doctrine->getRepository(Charge::class)->findBy(['user' => $user]);

        $html = $this->renderView('pdfs/dash_pdf.html.twig', [
            'user' => $user,
            'charges' => $charges,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio_dashboard.pdf"',
        ]);
    }
}