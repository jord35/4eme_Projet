<?php

class DateHelper
{
    public function formatMemberSince(string $createdAt): string
    {
        if ($createdAt === '') {
            return '';
        }

        try {
            $createdDate = new DateTimeImmutable($createdAt);
            $today = new DateTimeImmutable('now');
        } catch (Exception $exception) {
            return $createdAt;
        }

        if ($createdDate > $today) {
            return '';
        }

        $diff = $createdDate->diff($today);
        $days = (int) $diff->days;

        if ($days < 7) {
            return "moins d'une semaine";
        }

        if ($days < 30) {
            $weeks = max(1, (int) floor($days / 7));
            return $weeks . ' ' . ($weeks > 1 ? 'semaines' : 'semaine');
        }

        if ($days < 365) {
            $months = max(1, (int) floor($days / 30));
            return $months . ' mois';
        }

        $years = max(1, (int) floor($days / 365));
        return $years . ' ' . ($years > 1 ? 'ans' : 'an');
    }

    public function formatMessageListTime(string $value): string
    {
        return $this->formatDateTimeValue($value, 'H:i');
    }

    public function formatConversationMessageDateTime(string $value): string
    {
        return $this->formatDateTimeValue($value, 'd.m H:i');
    }

    private function formatDateTimeValue(string $value, string $format): string
    {
        if ($value === '') {
            return '';
        }

        try {
            return (new DateTimeImmutable($value))->format($format);
        } catch (Exception $exception) {
            return $value;
        }
    }
}
