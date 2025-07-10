<?php
namespace App\Controller;


use App\Document\Charge;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    
    #[Route('/api/dashboard/dados-cobrancas', name: 'dados_cobrancas')]
    public function dadosCobrancas(DocumentManager $dm): JsonResponse {
        $user = $this->getUser();
        $userId = $user->getId(); 
        $cobrancas = $dm->getRepository(Charge::class)->findBy(['userId' => $userId]);

        // Gráfico 1: Status Geral
        $statusCounts = [
            'PAGO' => $dm->createQueryBuilder(Charge::class)
                ->field('owner')->equals($user)
                ->field('status')->equals('PAGO')
                ->count()
                ->getQuery()
                ->execute(),

            'PENDENTE' => $dm->createQueryBuilder(Charge::class)
                ->field('owner')->equals($user)
                ->field('status')->equals('PENDENTE')
                ->count()
                ->getQuery()
                ->execute(),

            'VENCIDO' => $dm->createQueryBuilder(Charge::class)
                ->field('owner')->equals($user)
                ->field('status')->equals('VENCIDO')
                ->count()
                ->getQuery()
                ->execute(),
        ];

        $anoAtual = date('Y');

        // Gráfico 2: Status por Mês
        $statusPorMes = [
            'labels' => [],
            'PAGO' => [],
            'PENDENTE' => [],
            'VENCIDO' => [],
        ];
        
        for ($i = 1; $i <= 12; $i++) {
            $inicio = new \DateTime("$anoAtual-$i-01");
            $fim = (clone $inicio)->modify('last day of this month');
        
            $statusPorMes['labels'][] = $inicio->format('F');
        
            foreach (['PAGO', 'PENDENTE', 'VENCIDO'] as $status) {
                $qtd = $dm->createQueryBuilder(Charge::class)
                    ->field('owner')->equals($user)
                    ->field('status')->equals($status)
                    ->field('dueDate')->gte($inicio)->lte($fim)
                    ->count()
                    ->getQuery()
                    ->execute();
        
                $statusPorMes[$status][] = $qtd;
            }
        }
        
        // Gráfico 3: Receita Acumulada
        $receitaMensal = [];
        $acumulado = 0;
        
        for ($i = 1; $i <= 12; $i++) {
            $inicio = new \DateTime("$anoAtual-$i-01");
            $fim = (clone $inicio)->modify('last day of this month');
        
            $valorMes = $dm->createQueryBuilder(Charge::class)
                ->field('owner')->equals($user)
                ->field('status')->equals('PAGO')
                ->field('dueDate')->gte($inicio)->lte($fim)
                ->select('amount')
                ->hydrate(false)
                ->getQuery()
                ->execute()
                ->toArray();
        
            $mesTotal = array_sum(array_column($valorMes, 'amount'));
            $acumulado += $mesTotal;
            $receitaMensal[] = $acumulado;
        }
        
        // Gráfico 4: Faixa de Valores
        $faixasPorMes = [
            'labels' => [],
            '0-100' => [],
            '101-300' => [],
            '301-500' => [],
            '501-1000' => [],
        ];
        
        for ($i = 1; $i <= 12; $i++) {
            $inicio = new \DateTime("$anoAtual-$i-01");
            $fim = (clone $inicio)->modify('last day of this month');
        
            $faixasPorMes['labels'][] = $inicio->format('F');
        
            $faixas = [
                ['min' => 0, 'max' => 100],
                ['min' => 101, 'max' => 300],
                ['min' => 301, 'max' => 500],
                ['min' => 501, 'max' => 1000],
                ['min' => 1001, 'max' => 2000],
                ['min' => 2001, 'max' => 5000],
            ];
        
            foreach ($faixas as $faixa) {
                $count = $dm->createQueryBuilder(Charge::class)
                    ->field('owner')->equals($user)
                    ->field('amount')->gte($faixa['min'])->lte($faixa['max'])
                    ->field('dueDate')->gte($inicio)->lte($fim)
                    ->count()
                    ->getQuery()
                    ->execute();
        
                $key = "{$faixa['min']}-{$faixa['max']}";
                $faixasPorMes[$key][] = $count;
            }
        }

        return $this->json([
            'labels' => $statusPorMes['labels'],
            'pagas' => $statusPorMes['PAGO'],
            'pendentes' => $statusPorMes['PENDENTE'],
            'vencidas' => $statusPorMes['VENCIDO'],
            'receitaAcumulada' => $receitaMensal,
            'faixas' => $faixasPorMes,
        ]);
    }
}


