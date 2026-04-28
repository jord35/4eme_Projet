<?php

class MessagesController extends AbstractController
{
    private MessagePageService $messagePageService;

    public function __construct()
    {
        $this->messagePageService = new MessagePageService();
    }

    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($this->isConversationSummariesRequest()) {
                $this->handleConversationSummariesRequest();
                return;
            }

            if ($this->isUpdatesRequest()) {
                $this->handleUpdatesRequest();
                return;
            }

            $this->handlePageRequest();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSendMessageRequest();
            return;
        }

        http_response_code(405);

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => false,
                'error' => 'Method Not Allowed',
                'data' => null
            ]);
            return;
        }

        echo 'Method Not Allowed';
    }

    private function handlePageRequest(): void
    {
        $conversationId = isset($_GET['conversation_id']) ? (int) $_GET['conversation_id'] : null;
        $otherUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;

        $pageResult = $this->messagePageService->getPageData($conversationId, $otherUserId);

        if ($pageResult['success'] === false) {
            $this->handleError($pageResult['error']);
            return;
        }

        $pageData = $pageResult['data'] ?? [];

        $view = new View('Messages');
        $view->render('messages', [
            'currentUserId' => (int) ($pageData['currentUserId'] ?? 0),
            'activeConversationId' => $pageData['activeConversationId'] ?? null,
            'conversationSummaries' => $pageData['conversationSummaries'] ?? [],
            'messages' => $pageData['messages'] ?? [],
            'unreadConversationCount' => (int) ($pageData['unreadConversationCount'] ?? 0),
            'globalUnreadMessageCount' => (int) ($pageData['unreadMessageCount'] ?? 0)
        ]);
    }

    private function handleUpdatesRequest(): void
    {
        $conversationId = isset($_GET['conversation_id']) ? (int) $_GET['conversation_id'] : 0;
        $afterId = isset($_GET['after_id']) ? (int) $_GET['after_id'] : 0;

        $updatesResult = $this->messagePageService->getConversationUpdates($conversationId, $afterId);

        if ($updatesResult['success'] === false) {
            $this->handleError($updatesResult['error']);
            return;
        }

        $this->renderJson([
            'success' => true,
            'error' => null,
            'data' => $updatesResult['data']
        ]);
    }

    private function handleConversationSummariesRequest(): void
    {
        $result = $this->messagePageService->getConversationSummariesData();

        if ($result['success'] === false) {
            $this->handleError($result['error']);
            return;
        }

        $this->renderJson([
            'success' => true,
            'error' => null,
            'data' => $result['data']
        ]);
    }

    private function handleSendMessageRequest(): void
    {
        $sendResult = $this->messagePageService->sendMessage($_POST);

        if ($sendResult['success'] === false) {
            $this->handleError($sendResult['error']);
            return;
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => true,
                'error' => null,
                'data' => $sendResult['data']
            ]);
            return;
        }

        $message = $sendResult['data']['message'] ?? null;
        $conversationId = isset($message['conversation_id']) ? (int) $message['conversation_id'] : 0;

        header('Location: /?action=messages&conversation_id=' . $conversationId);
        exit;
    }

    private function isUpdatesRequest(): bool
    {
        return isset($_GET['ajax']) && $_GET['ajax'] === 'updates';
    }

    private function isConversationSummariesRequest(): bool
    {
        return isset($_GET['ajax']) && $_GET['ajax'] === 'conversation-summaries';
    }

    private function handleError(?string $error): void
    {
        $error = $error ?? 'Une erreur est survenue.';
        $statusCode = 500;

        if ($error === 'Authentication required.') {
            $statusCode = 403;
        } elseif (
            $error === 'Conversation not found or access denied.' ||
            $error === 'Cannot create conversation with yourself.'
        ) {
            $statusCode = 404;
        } elseif (
            $error === 'Invalid user id.' ||
            $error === 'Invalid conversation id.' ||
            $error === 'Invalid message id.' ||
            $error === 'Message content is required.' ||
            $error === 'Conversation creation failed.' ||
            $error === 'Conversation participants creation failed.' ||
            $error === 'Message send failed.' ||
            $error === 'Message not found after creation.' ||
            $error === 'Conversation read state update failed.'
        ) {
            $statusCode = 422;
        }

        http_response_code($statusCode);

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => false,
                'error' => $error,
                'data' => null
            ]);
            return;
        }

        echo $error;
    }
}