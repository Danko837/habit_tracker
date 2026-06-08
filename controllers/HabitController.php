<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Habit.php';

class HabitController
{
    private Habit $habitModel;

    public function __construct(Database $database)
    {
        $this->habitModel = new Habit($database);
    }

    public function index(): void
    {
        requireLogin();
        $habits = $this->habitModel->allForUser((int) $_SESSION['user_id']);
        view('habits/index', ['habits' => $habits]);
    }

    public function create(): void
    {
        requireLogin();
        $habit = ['title' => '', 'description' => '', 'frequency' => 'daily', 'target_count' => 1, 'start_date' => date('Y-m-d'), 'is_active' => 1];
        view('habits/form', ['habit' => $habit, 'action' => url('/habits/store'), 'title' => 'Nový návyk', 'button' => 'Vytvoriť návyk']);
    }

    public function store(): void
    {
        requireLogin();
        verifyCsrf();
        [$data, $error] = $this->validatedData();
        if ($error) {
            view('habits/form', ['habit' => $data, 'action' => url('/habits/store'), 'title' => 'Nový návyk', 'button' => 'Vytvoriť návyk', 'error' => $error]);
            return;
        }
        $this->habitModel->create((int) $_SESSION['user_id'], $data);
        flash('success', 'Návyk bol vytvorený.');
        redirect('/habits');
    }

    public function edit(int $id): void
    {
        requireLogin();
        $habit = $this->habitModel->findForUser($id, (int) $_SESSION['user_id']);
        if (!$habit) {
            http_response_code(404);
            view('404');
            return;
        }
        view('habits/form', ['habit' => $habit, 'action' => url('/habits/update') . '?id=' . $id, 'title' => 'Upraviť návyk', 'button' => 'Uložiť zmeny']);
    }

    public function update(int $id): void
    {
        requireLogin();
        verifyCsrf();
        [$data, $error] = $this->validatedData();
        if ($error) {
            view('habits/form', ['habit' => $data, 'action' => url('/habits/update') . '?id=' . $id, 'title' => 'Upraviť návyk', 'button' => 'Uložiť zmeny', 'error' => $error]);
            return;
        }
        $this->habitModel->update($id, (int) $_SESSION['user_id'], $data);
        flash('success', 'Návyk bol upravený.');
        redirect('/habits');
    }

    public function delete(int $id): void
    {
        requireLogin();
        verifyCsrf();
        $this->habitModel->delete($id, (int) $_SESSION['user_id']);
        flash('success', 'Návyk bol vymazaný.');
        redirect('/habits');
    }

    public function toggle(int $id): void
    {
        requireLogin();
        verifyCsrf();
        $this->habitModel->toggleToday($id, (int) $_SESSION['user_id']);
        redirect('/habits');
    }

    public function progress(): void
    {
        requireLogin();
        $stats = $this->habitModel->statsForUser((int) $_SESSION['user_id']);
        $logs = $this->habitModel->logsForUser((int) $_SESSION['user_id']);
        view('progress', ['stats' => $stats, 'logs' => $logs]);
    }

    private function validatedData(): array
    {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'frequency' => $_POST['frequency'] ?? 'daily',
            'target_count' => (int) ($_POST['target_count'] ?? 1),
            'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        if ($data['title'] === '') {
            return [$data, 'Názov návyku je povinný.'];
        }
        if (!in_array($data['frequency'], ['daily', 'weekly', 'custom'], true)) {
            return [$data, 'Neplatná frekvencia.'];
        }
        if ($data['target_count'] < 1 || $data['target_count'] > 100) {
            return [$data, 'Cieľ musí byť číslo od 1 do 100.'];
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['start_date'])) {
            return [$data, 'Neplatný dátum začiatku.'];
        }

        return [$data, null];
    }
}
