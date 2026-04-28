<?php

class MessagingService
{
    private ConversationManager $conversationManager;
    private MessageManager $messageManager;

    public function __construct()
    {
        $this->conversationManager = new ConversationManager();
        $this->messageManager = new MessageManager();
    }

    public function getUserConversationSummaries(int $userId): array
    {
        $error = $this->validateUserId($userId);
        if ($error !== null) {
            return $error;
        }

        $conversations = $this->conversationManager->findConversationSummariesByUserId($userId);

        return $this->successResponse($conversations);
    }

    public function getOrCreateConversationBetweenUsers(int $currentUserId, int $otherUserId): array
    {
        $error = $this->validateDistinctUsers($currentUserId, $otherUserId);
        if ($error !== null) {
            return $error;
        }

        $existingConversation = $this->conversationManager->findConversationBetweenUsers(
            $currentUserId,
            $otherUserId
        );

        if ($existingConversation !== null) {
            return $this->successResponse([
                'conversation_id' => (int) $existingConversation['conversation_id'],
                'created' => false
            ]);
        }

        $conversationId = $this->conversationManager->createConversation();

        if ($conversationId <= 0) {
            return $this->errorResponse('Conversation creation failed.');
        }

        $participantsAdded = $this->conversationManager->addParticipants(
            $conversationId,
            [$currentUserId, $otherUserId]
        );

        if ($participantsAdded === false) {
            return $this->errorResponse('Conversation participants creation failed.');
        }

        return $this->successResponse([
            'conversation_id' => $conversationId,
            'created' => true
        ]);
    }

    public function getConversationMessages(int $conversationId, int $userId): array
    {
        return $this->fetchConversationMessages($conversationId, $userId, null);
    }

    public function getConversationMessagesAfterId(int $conversationId, int $userId, int $afterId): array
    {
        if ($afterId < 0) {
            return $this->errorResponse('Invalid message id.');
        }

        return $this->fetchConversationMessages($conversationId, $userId, $afterId);
    }

    public function sendMessage(int $conversationId, int $senderUserId, string $content): array
    {
        $content = trim($content);

        $accessError = $this->validateConversationAccess($conversationId, $senderUserId);
        if ($accessError !== null) {
            return $accessError;
        }

        if ($content === '') {
            return $this->errorResponse('Message content is required.');
        }

        $messageId = $this->messageManager->insertMessage($conversationId, $senderUserId, $content);

        if ($messageId <= 0) {
            return $this->errorResponse('Message send failed.');
        }

        $this->conversationManager->touchConversation($conversationId);

        $lastMessage = $this->messageManager->findLastMessageByConversationId($conversationId);

        if ($lastMessage === null) {
            return $this->errorResponse('Message not found after creation.');
        }

        return $this->successResponse($lastMessage);
    }

    public function markConversationAsRead(int $conversationId, int $userId): array
    {
        $accessError = $this->validateConversationAccess($conversationId, $userId);
        if ($accessError !== null) {
            return $accessError;
        }

        $updated = $this->conversationManager->markConversationAsRead($conversationId, $userId);

        if ($updated === false) {
            return $this->errorResponse('Conversation read state update failed.');
        }

        return $this->successResponse([
            'conversation_id' => $conversationId
        ]);
    }

    public function getUnreadConversationCount(int $userId): array
    {
        $error = $this->validateUserId($userId);
        if ($error !== null) {
            return $error;
        }

        return $this->successResponse([
            'count' => $this->conversationManager->countUnreadConversationsByUserId($userId)
        ]);
    }

    public function getUnreadMessageCount(int $userId): array
    {
        $error = $this->validateUserId($userId);
        if ($error !== null) {
            return $error;
        }

        return $this->successResponse([
            'count' => $this->conversationManager->countUnreadMessagesByUserId($userId)
        ]);
    }

    private function fetchConversationMessages(int $conversationId, int $userId, ?int $afterId): array
    {
        $accessError = $this->validateConversationAccess($conversationId, $userId);
        if ($accessError !== null) {
            return $accessError;
        }

        $messages = $afterId === null
            ? $this->messageManager->findMessagesByConversationId($conversationId)
            : $this->messageManager->findMessagesByConversationIdAfterId($conversationId, $afterId);

        return $this->successResponse($messages);
    }

    private function validateUserId(int $userId): ?array
    {
        if ($userId <= 0) {
            return $this->errorResponse('Invalid user id.');
        }

        return null;
    }

    private function validateConversationId(int $conversationId): ?array
    {
        if ($conversationId <= 0) {
            return $this->errorResponse('Invalid conversation id.');
        }

        return null;
    }

    private function validateDistinctUsers(int $currentUserId, int $otherUserId): ?array
    {
        if ($currentUserId <= 0 || $otherUserId <= 0) {
            return $this->errorResponse('Invalid user id.');
        }

        if ($currentUserId === $otherUserId) {
            return $this->errorResponse('Cannot create conversation with yourself.');
        }

        return null;
    }

    private function validateConversationAccess(int $conversationId, int $userId): ?array
    {
        $conversationError = $this->validateConversationId($conversationId);
        if ($conversationError !== null) {
            return $conversationError;
        }

        $userError = $this->validateUserId($userId);
        if ($userError !== null) {
            return $userError;
        }

        if (!$this->conversationManager->isUserParticipant($conversationId, $userId)) {
            return $this->errorResponse('Conversation not found or access denied.');
        }

        return null;
    }

    private function successResponse($data): array
    {
        return [
            'success' => true,
            'error' => null,
            'data' => $data
        ];
    }

    private function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'error' => $message,
            'data' => null
        ];
    }
}