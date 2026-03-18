<?php

class MessageController
{
    public function index(): void
    {
        $messageManager = new MessageManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($title !== '' && $content !== '') {
                $message = new Message();
                $message->setTitle($title);
                $message->setContent($content);

                $messageManager->add($message);
            }

            header('Location: index.php');
            exit;
        }

        if (isset($_GET['ajax']) && $_GET['ajax'] === 'messages') {
            $afterId = (int) ($_GET['afterId'] ?? 0);
            $messages = $messageManager->findAfterId($afterId);

            header('Content-Type: application/json; charset=utf-8');

            ob_start();
            foreach ($messages as $message) {
                require __DIR__ . '/../views/templates/message/_item.php';
            }
            $html = ob_get_clean();

            $lastId = $afterId;
            if (!empty($messages)) {
                $lastMessage = end($messages);
                $lastId = $lastMessage->getId();
            }

            echo json_encode([
                'html' => $html,
                'lastId' => $lastId
            ]);
            exit;
        }

        $messages = $messageManager->findAll();

        $view = new View('Test messages');
        $view->render('message/index', [
            'messages' => $messages
        ]);
    }
}