
<?php

class MessageController extends AbstractController
{
    public function index(): void
    {
        $messageManager = new MessageManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($messageManager);
            return;
        }

        if ($this->isMessagesAjaxRequest()) {
            $this->handleAjaxMessages($messageManager);
            return;
        }

        $this->renderIndex($messageManager);
    }

    private function handlePost(MessageManager $messageManager): void
    {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title !== '' && $content !== '') {
            $message = new Message();
            $message->setTitle($title);
            $message->setContent($content);

            $messageManager->add($message);
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson([
                'success' => true
            ]);
        }

        header('Location: index.php');
        exit;
    }

    private function handleAjaxMessages(MessageManager $messageManager): void
    {
        $afterId = (int) ($_GET['afterId'] ?? 0);
        $messages = $messageManager->findAfterId($afterId);

        ob_start();
        foreach ($messages as $message) {
            require __DIR__ . '/../../views/templates/message/_item.php';
        }
        $html = ob_get_clean();

        $lastId = $afterId;

        if (!empty($messages)) {
            $lastMessage = end($messages);
            $lastId = $lastMessage->getId();
        }

        $this->renderJson([
            'html' => $html,
            'lastId' => $lastId
        ]);
    }

    private function renderIndex(MessageManager $messageManager): void
    {
        $messages = $messageManager->findAll();

        $view = new View('Test messages');
        $view->render('message/index', [
            'messages' => $messages
        ]);
    }

    private function isMessagesAjaxRequest(): bool
    {
        return isset($_GET['ajax']) && $_GET['ajax'] === 'messages';
    }
}
