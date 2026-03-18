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

        $messages = $messageManager->findAll();

        $view = new View('Test messages');
        $view->render('message/index', [
            'messages' => $messages
        ]);
    }
}
