<?php

abstract class AbstractController
{
    protected function isAjaxRequest(): bool
    {
        return true;
    }

    protected function renderJson(array $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
