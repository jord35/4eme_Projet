<?php

class MessageManager extends AbstractEntityManager
{
    private function mapMessageRow(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'conversation_id' => (int) $row['conversation_id'],
            'sender_user_id' => (int) $row['sender_user_id'],
            'sender_username' => (string) $row['sender_username'],
            'content' => (string) $row['content'],
            'created_at' => (string) $row['created_at'],
        ];
    }

    private function mapMessageRows(array $rows): array
    {
        return array_map([$this, 'mapMessageRow'], $rows);
    }


    public function findMessagesByConversationId(int $conversationId): array
    {
        if ($conversationId <= 0) {
            return [];
        }

        $sql = '
            SELECT
                m.id,
                m.conversation_id,
                m.sender_user_id,
                u.username AS sender_username,
                m.content,
                m.created_at
            FROM messages m
            INNER JOIN users u ON u.id = m.sender_user_id
            WHERE m.conversation_id = :conversation_id
            ORDER BY m.created_at ASC, m.id ASC
        ';

        $stmt = $this->db->query($sql, [
            'conversation_id' => $conversationId
        ]);

        $rows = $stmt->fetchAll();

        if (!$rows) {
            return [];
        }

        return $this->mapMessageRows($rows);
    }


    public function findMessagesByConversationIdAfterId(int $conversationId, int $afterId): array
    {
        if ($conversationId <= 0) 
            {
            return [];
            }

        $sql = '
            SELECT
                m.id,
                m.conversation_id,
                m.sender_user_id,
                u.username AS sender_username,
                m.content,
                m.created_at
            FROM messages m
            INNER JOIN users u ON u.id = m.sender_user_id
            WHERE m.conversation_id = :conversation_id
              AND m.id > :after_id
            ORDER BY m.created_at ASC, m.id ASC
        ';

        $stmt = $this->db->query($sql, [
            'conversation_id' => $conversationId,
            'after_id' => max(0, $afterId)
        ]);

        $rows = $stmt->fetchAll();

        if (!$rows) 
            {
            return [];
            }

        return array_map([$this, 'mapMessageRow'], $rows);
    }

    public function insertMessage(int $conversationId, int $senderUserId, string $content): int
    {
        $content = trim($content);

        if ($conversationId <= 0 || $senderUserId <= 0 || $content === '') {
            return 0;
        }

        $sql = '
            INSERT INTO messages (
                conversation_id,
                sender_user_id,
                content,
                created_at
            ) VALUES (
                :conversation_id,
                :sender_user_id,
                :content,
                NOW()
            )
        ';

        $this->db->query($sql, [
            'conversation_id' => $conversationId,
            'sender_user_id' => $senderUserId,
            'content' => $content
        ]);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    public function findLastMessageByConversationId(int $conversationId): ?array
    {
        if ($conversationId <= 0) {
            return null;
        }

        $sql = '
            SELECT
                m.id,
                m.conversation_id,
                m.sender_user_id,
                u.username AS sender_username,
                m.content,
                m.created_at
            FROM messages m
            INNER JOIN users u ON u.id = m.sender_user_id
            WHERE m.conversation_id = :conversation_id
            ORDER BY m.created_at DESC, m.id DESC
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'conversation_id' => $conversationId
        ]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row['id'],
            'conversation_id' => (int) $row['conversation_id'],
            'sender_user_id' => (int) $row['sender_user_id'],
            'sender_username' => (string) $row['sender_username'],
            'content' => (string) $row['content'],
            'created_at' => (string) $row['created_at']
        ];
    }
}