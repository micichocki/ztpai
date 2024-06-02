<?php

namespace App\Controller;

use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class IssueController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($request->isMethod('PUT')) {
            return $this->setIssueStatus($request, $entityManager);
        } else {
            return new JsonResponse(null, 403);
        }
    }
    #[Route('/issues/{id}/update_status', name: 'update_issue_status', methods: ['POST'])]
    private function setIssueStatus(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $issueId = $request->attributes->get('id');

        $issue = $entityManager->getRepository(Issue::class)->find($issueId);

        if (!$issue) {
            return new JsonResponse(['error' => 'Issue not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['isResolved'])) {
            return new JsonResponse(['error' => 'Missing required field: isResolved'], 400);
        }

        $issue->setIsResolved($data['isResolved']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Issue status updated successfully', 'issue' => $issue],200);
    }
}
