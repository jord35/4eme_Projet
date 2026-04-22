<?php

class MessagePageService
{
    private AuthenticationService $authenticationService;
    private MessagingService $messagingService;
    private PictureHelper $pictureHelper;
    private DateHelper $dateHelper;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->messagingService = new MessagingService();
        $this->pictureHelper = new PictureHelper();
        $this->dateHelper = new DateHelper();
    }

    public function getPageData(?int $conversationId = null, ?int $otherUserId = null): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $currentUserId = (int) $authResult['data']['user_id'];

        $conversationsResult = $this->messagingService->getUserConversationSummaries($currentUserId);

        if ($conversationsResult['success'] === false) {
            return $conversationsResult;
        }

        $conversationSummaries = $this->formatConversationSummaries(
            $this->hydrateConversationPictures($conversationsResult['data'] ?? [])
        );
        $activeConversationId = null;
        $messages = [];

        if ($otherUserId !== null && $otherUserId > 0) {
            $conversationResult = $this->messagingService->getOrCreateConversationBetweenUsers(
                $currentUserId,
                $otherUserId
            );

            if ($conversationResult['success'] === false) {
                return $conversationResult;
            }

            $activeConversationId = (int) ($conversationResult['data']['conversation_id'] ?? 0);
        } elseif ($conversationId !== null && $conversationId > 0) {
            $activeConversationId = $conversationId;
        } elseif (!empty($conversationSummaries)) {
            $activeConversationId = (int) $conversationSummaries[0]['conversation_id'];
        }

        if ($activeConversationId !== null && $activeConversationId > 0) {
            $messagesResult = $this->messagingService->getConversationMessages(
                $activeConversationId,
                $currentUserId
            );

            if ($messagesResult['success'] === false) {
                return $messagesResult;
            }

            $messages = $this->formatMessages($messagesResult['data'] ?? []);

            $markReadResult = $this->messagingService->markConversationAsRead(
                $activeConversationId,
                $currentUserId
            );

            if ($markReadResult['success'] === false) {
                return $markReadResult;
            }

            $conversationsResult = $this->messagingService->getUserConversationSummaries($currentUserId);

            if ($conversationsResult['success'] === false) {
                return $conversationsResult;
            }

            $conversationSummaries = $this->formatConversationSummaries(
                $this->hydrateConversationPictures($conversationsResult['data'] ?? [])
            );
        }

        $unreadCountersResult = $this->getUnreadCounters($currentUserId);

        if ($unreadCountersResult['success'] === false) {
            return $unreadCountersResult;
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'currentUserId' => $currentUserId,
                'activeConversationId' => $activeConversationId,
                'conversationSummaries' => $conversationSummaries,
                'messages' => $messages,
                'unreadConversationCount' => (int) ($unreadCountersResult['data']['unreadConversationCount'] ?? 0),
                'unreadMessageCount' => (int) ($unreadCountersResult['data']['unreadMessageCount'] ?? 0)
            ]
        ];
    }

    public function getConversationUpdates(int $conversationId, int $afterId): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $currentUserId = (int) $authResult['data']['user_id'];

        $messagesResult = $this->messagingService->getConversationMessagesAfterId(
            $conversationId,
            $currentUserId,
            $afterId
        );

        if ($messagesResult['success'] === false) {
            return $messagesResult;
        }

        $markReadResult = $this->messagingService->markConversationAsRead(
            $conversationId,
            $currentUserId
        );

        if ($markReadResult['success'] === false) {
            return $markReadResult;
        }

        $unreadCountersResult = $this->getUnreadCounters($currentUserId);

        if ($unreadCountersResult['success'] === false) {
            return $unreadCountersResult;
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'messages' => $this->formatMessages($messagesResult['data'] ?? []),
                'unreadConversationCount' => (int) ($unreadCountersResult['data']['unreadConversationCount'] ?? 0),
                'unreadMessageCount' => (int) ($unreadCountersResult['data']['unreadMessageCount'] ?? 0)
            ]
        ];
    }

    public function sendMessage(array $post): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $currentUserId = (int) $authResult['data']['user_id'];
        $conversationId = isset($post['conversation_id']) ? (int) $post['conversation_id'] : 0;
        $content = trim($post['content'] ?? '');

        $sendResult = $this->messagingService->sendMessage(
            $conversationId,
            $currentUserId,
            $content
        );

        if ($sendResult['success'] === false) {
            return $sendResult;
        }

        $unreadCountersResult = $this->getUnreadCounters($currentUserId);

        if ($unreadCountersResult['success'] === false) {
            return $unreadCountersResult;
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'message' => $this->formatMessage($sendResult['data'] ?? []),
                'unreadConversationCount' => (int) ($unreadCountersResult['data']['unreadConversationCount'] ?? 0),
                'unreadMessageCount' => (int) ($unreadCountersResult['data']['unreadMessageCount'] ?? 0)
            ]
        ];
    }

    public function getUnreadBadgeData(): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return [
                'success' => true,
                'error' => null,
                'data' => [
                    'count' => 0
                ]
            ];
        }

        $currentUserId = (int) $authResult['data']['user_id'];

        return $this->messagingService->getUnreadMessageCount($currentUserId);
    }
    private function getUnreadCounters(int $currentUserId): array
    {
        $unreadConversationCountResult = $this->messagingService->getUnreadConversationCount($currentUserId);

        if ($unreadConversationCountResult['success'] === false) {
            return $unreadConversationCountResult;
        }

        $unreadMessageCountResult = $this->messagingService->getUnreadMessageCount($currentUserId);

        if ($unreadMessageCountResult['success'] === false) {
            return $unreadMessageCountResult;
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'unreadConversationCount' => (int) ($unreadConversationCountResult['data']['count'] ?? 0),
                'unreadMessageCount' => (int) ($unreadMessageCountResult['data']['count'] ?? 0),
            ]
        ];
    }
    public function getConversationSummariesData(): array
    {
        $authResult = $this->authenticationService->requireUserId();

        if ($authResult['success'] === false) {
            return $authResult;
        }

        $currentUserId = (int) $authResult['data']['user_id'];

        $conversationsResult = $this->messagingService->getUserConversationSummaries($currentUserId);

        if ($conversationsResult['success'] === false) {
            return $conversationsResult;
        }

        $unreadCountersResult = $this->getUnreadCounters($currentUserId);

        if ($unreadCountersResult['success'] === false) {
            return $unreadCountersResult;
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'conversationSummaries' => $this->formatConversationSummaries(
                    $this->hydrateConversationPictures($conversationsResult['data'] ?? [])
                ),
                'unreadConversationCount' => (int) ($unreadCountersResult['data']['unreadConversationCount'] ?? 0),
                'unreadMessageCount' => (int) ($unreadCountersResult['data']['unreadMessageCount'] ?? 0)
            ]
        ];
    }

    private function hydrateConversationPictures(array $conversationSummaries): array
    {
        return array_map(function (array $conversation): array {
            $conversation['other_user_picture'] = null;

            if (empty($conversation['other_user_profile_picture_id'])) {
                return $conversation;
            }

            $pictureResult = $this->pictureHelper->getPicturePackage(
                (int) $conversation['other_user_profile_picture_id'],
                'profile'
            );

            if ($pictureResult['success'] === true) {
                $conversation['other_user_picture'] = (string) ($pictureResult['data']['src'] ?? '');
            }

            return $conversation;
        }, $conversationSummaries);
    }

    private function formatConversationSummaries(array $conversationSummaries): array
    {
        return array_map(function (array $conversation): array {
            $conversation['last_message_created_at'] = $this->dateHelper->formatMessageListTime(
                (string) ($conversation['last_message_created_at'] ?? '')
            );

            return $conversation;
        }, $conversationSummaries);
    }

    private function formatMessages(array $messages): array
    {
        return array_map(fn(array $message): array => $this->formatMessage($message), $messages);
    }

    private function formatMessage(array $message): array
    {
        if (empty($message)) {
            return $message;
        }

        $message['created_at'] = $this->dateHelper->formatConversationMessageDateTime(
            (string) ($message['created_at'] ?? '')
        );

        return $message;
    }
}
