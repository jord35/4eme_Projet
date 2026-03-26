<?php

class MessageManager extends AbstractEntityManager
{
    public function findAll(): array
    {
        $sql = "SELECT * FROM message ORDER BY id DESC";
        $stmt = $this->db->query($sql);

        $messages = [];

        while ($row = $stmt->fetch()) {
            $messages[] = new Message($row);
        }

        return $messages;
    }

    public function add(Message $message): Message
    {
        $sql = "INSERT INTO message (title, content)
                VALUES (:title, :content)";

        $this->db->query($sql, [
            'title' => $message->getTitle(),
            'content' => $message->getContent()
        ]);

        $message->setId((int) $this->db->getPDO()->lastInsertId());

        return $message;
    }
    public function findAfterId(int $afterId): array
    {
        $sql = "SELECT * FROM message WHERE id > :afterId ORDER BY id DESC";
        $stmt = $this->db->query($sql, [
            'afterId' => $afterId
        ]);

        $messages = [];

        while ($row = $stmt->fetch()) {
            $messages[] = new Message($row);
        }

        return $messages;
    }
}