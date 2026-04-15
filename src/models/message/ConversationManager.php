<?php

class ConversationManager extends AbstractEntityManager
{
    public function findConversationIdsByUserId(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $sql = '
            SELECT conversation_id
            FROM conversation_participants
            WHERE user_id = :user_id
            ORDER BY conversation_id DESC
        ';

        $stmt = $this->db->query($sql, [
            'user_id' => $userId
        ]);

        $rows = $stmt->fetchAll();

        return array_map(static function (array $row): int {
            return (int) $row['conversation_id'];
        }, $rows ?: []);
    }

    public function findConversationBetweenUsers(int $firstUserId, int $secondUserId): ?array
    {
        if ($firstUserId <= 0 || $secondUserId <= 0 || $firstUserId === $secondUserId) {
            return null;
        }

        $sql = '
            SELECT cp1.conversation_id
            FROM conversation_participants cp1
            INNER JOIN conversation_participants cp2
                ON cp2.conversation_id = cp1.conversation_id
            WHERE cp1.user_id = :first_user_id
              AND cp2.user_id = :second_user_id
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'first_user_id' => $firstUserId,
            'second_user_id' => $secondUserId
        ]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'conversation_id' => (int) $row['conversation_id']
        ];
    }

    public function createConversation(): int
    {
        $sql = '
            INSERT INTO conversations (
                created_at,
                updated_at
            ) VALUES (
                NOW(),
                NOW()
            )
        ';

        $this->db->query($sql);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    public function addParticipant(int $conversationId, int $userId): bool
    {
        if ($conversationId <= 0 || $userId <= 0) {
            return false;
        }

        $sql = '
            INSERT INTO conversation_participants (
                conversation_id,
                user_id,
                last_read_at,
                joined_at
            ) VALUES (
                :conversation_id,
                :user_id,
                NULL,
                NOW()
            )
        ';

        try {
            $this->db->query($sql, [
                'conversation_id' => $conversationId,
                'user_id' => $userId
            ]);

            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }

    public function addParticipants(int $conversationId, array $userIds): bool
    {
        if ($conversationId <= 0 || empty($userIds)) {
            return false;
        }

        $success = true;

        foreach ($userIds as $userId) {
            $added = $this->addParticipant($conversationId, (int) $userId);

            if ($added === false) {
                $success = false;
            }
        }

        return $success;
    }

    public function isUserParticipant(int $conversationId, int $userId): bool
    {
        if ($conversationId <= 0 || $userId <= 0) {
            return false;
        }

        $sql = '
            SELECT 1
            FROM conversation_participants
            WHERE conversation_id = :conversation_id
              AND user_id = :user_id
            LIMIT 1
        ';

        $stmt = $this->db->query($sql, [
            'conversation_id' => $conversationId,
            'user_id' => $userId
        ]);

        return (bool) $stmt->fetch();
    }

    public function markConversationAsRead(int $conversationId, int $userId): bool
    {
        if ($conversationId <= 0 || $userId <= 0) {
            return false;
        }

        $sql = '
            UPDATE conversation_participants
            SET last_read_at = NOW()
            WHERE conversation_id = :conversation_id
              AND user_id = :user_id
        ';

        $stmt = $this->db->query($sql, [
            'conversation_id' => $conversationId,
            'user_id' => $userId
        ]);

        return $stmt->rowCount() > 0 || $this->isUserParticipant($conversationId, $userId);
    }

    public function touchConversation(int $conversationId): bool
    {
        if ($conversationId <= 0) {
            return false;
        }

        $sql = '
            UPDATE conversations
            SET updated_at = NOW()
            WHERE id = :id
        ';

        $stmt = $this->db->query($sql, [
            'id' => $conversationId
        ]);

        return $stmt->rowCount() > 0;
    }

    public function countUnreadConversationsByUserId(int $userId): int
    {
        if ($userId <= 0) {
            return 0;
        }

        $sql = '
            SELECT COUNT(DISTINCT cp.conversation_id) AS unread_conversations_count
            FROM conversation_participants cp
            INNER JOIN messages m
                ON m.conversation_id = cp.conversation_id
            WHERE cp.user_id = :user_id
              AND m.sender_user_id != :user_id
              AND (
                    cp.last_read_at IS NULL
                    OR m.created_at > cp.last_read_at
                  )
        ';

        $stmt = $this->db->query($sql, [
            'user_id' => $userId
        ]);

        $row = $stmt->fetch();

        return (int) ($row['unread_conversations_count'] ?? 0);
    }

    public function countUnreadMessagesByUserId(int $userId): int
    {
        if ($userId <= 0) {
            return 0;
        }

        $sql = '
            SELECT COUNT(m.id) AS unread_messages_count
            FROM conversation_participants cp
            INNER JOIN messages m
                ON m.conversation_id = cp.conversation_id
            WHERE cp.user_id = :user_id
              AND m.sender_user_id != :user_id
              AND (
                    cp.last_read_at IS NULL
                    OR m.created_at > cp.last_read_at
                  )
        ';

        $stmt = $this->db->query($sql, [
            'user_id' => $userId
        ]);

        $row = $stmt->fetch();

        return (int) ($row['unread_messages_count'] ?? 0);
    }

    public function findConversationSummariesByUserId(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $sql = '
            SELECT
                cp.conversation_id,
                other_user.id AS other_user_id,
                other_user.username AS other_username,
                last_message.id AS last_message_id,
                last_message.content AS last_message_content,
                last_message.created_at AS last_message_created_at,
                (
                    SELECT COUNT(m_unread.id)
                    FROM messages m_unread
                    WHERE m_unread.conversation_id = cp.conversation_id
                      AND m_unread.sender_user_id != :user_id
                      AND (
                            cp.last_read_at IS NULL
                            OR m_unread.created_at > cp.last_read_at
                          )
                ) AS unread_count
            FROM conversation_participants cp
            INNER JOIN conversation_participants cp_other
                ON cp_other.conversation_id = cp.conversation_id
               AND cp_other.user_id != :user_id
            INNER JOIN users other_user
                ON other_user.id = cp_other.user_id
            LEFT JOIN messages last_message
                ON last_message.id = (
                    SELECT m2.id
                    FROM messages m2
                    WHERE m2.conversation_id = cp.conversation_id
                    ORDER BY m2.created_at DESC, m2.id DESC
                    LIMIT 1
                )
            WHERE cp.user_id = :user_id
            ORDER BY
                COALESCE(last_message.created_at, "1970-01-01 00:00:00") DESC,
                cp.conversation_id DESC
        ';

        $stmt = $this->db->query($sql, [
            'user_id' => $userId
        ]);

        $rows = $stmt->fetchAll();

        if (!$rows) {
            return [];
        }

        return array_map(static function (array $row): array {
            return [
                'conversation_id' => (int) $row['conversation_id'],
                'other_user_id' => (int) $row['other_user_id'],
                'other_username' => (string) $row['other_username'],
                'last_message_id' => isset($row['last_message_id']) ? (int) $row['last_message_id'] : null,
                'last_message_content' => $row['last_message_content'] !== null ? (string) $row['last_message_content'] : null,
                'last_message_created_at' => $row['last_message_created_at'] !== null ? (string) $row['last_message_created_at'] : null,
                'unread_count' => (int) $row['unread_count']
            ];
        }, $rows);
    }
}