<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\Schedule;
use App\Services\Mailer;
use PDOException;

final class EmployeeController extends Controller
{
    public function dashboard(): void
    {
        require_role('employe');

        $status = trim((string)($_GET['status'] ?? ''));
        $clientEmail = trim((string)($_GET['client_email'] ?? ''));

        $orders = Order::allByFilters($status, $clientEmail);
        $reviews = Review::pending();

        $this->view('employee/dashboard', [
            'orders' => $orders,
            'reviews' => $reviews,
            'status' => $status,
            'clientEmail' => $clientEmail,
        ]);
    }

    public function updateStatus(string $id): void
    {
        require_role('employe');

        $orderId = (int)$id;
        $status = trim((string)($_POST['status'] ?? 'recue'));

        Order::updateStatus($orderId, $status);

        if ($status === 'en_attente_retour_materiel') {
            $order = Order::findWithUserEmail($orderId);
            if (!empty($order['user_email'])) {
                $message = "Bonjour,\n\n" .
                    "Votre commande #{$orderId} est passee au statut 'en attente du retour de materiel'.\n" .
                    "Merci de restituer le materiel sous 10 jours ouvres.\n" .
                    "Passe ce delai, des frais de 600 EUR peuvent etre appliques conformement aux CGV.\n\n" .
                    "Cordialement,\nVite & Gourmand";

                Mailer::send((string)$order['user_email'], 'Retour de materiel - commande #' . $orderId, $message);
            }
        }

        flash('success', 'Statut mis a jour.');
        redirect('/employee');
    }

    public function cancelOrder(string $id): void
    {
        require_role('employe');

        $reason = trim((string)($_POST['reason'] ?? ''));
        $contactMode = trim((string)($_POST['contact_mode'] ?? ''));

        if ($reason === '' || $contactMode === '') {
            flash('error', 'Motif et mode de contact obligatoires.');
            redirect('/employee');
        }

        Order::cancel((int)$id, $reason, $contactMode);
        flash('success', 'Commande annulee.');
        redirect('/employee');
    }

    public function menus(): void
    {
        require_role('employe');

        $menus = Menu::all([], true);
        $themes = Menu::themes();
        $regimes = Menu::regimes();
        $dishes = Dish::all();
        $schedules = Schedule::all();

        $this->view('employee/menus', [
            'menus' => $menus,
            'themes' => $themes,
            'regimes' => $regimes,
            'dishes' => $dishes,
            'schedules' => $schedules,
        ]);
    }

    public function saveMenu(): void
    {
        require_role('employe');

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'theme_id' => (int)($_POST['theme_id'] ?? 0),
            'regime_id' => (int)($_POST['regime_id'] ?? 0),
            'min_people' => (int)($_POST['min_people'] ?? 0),
            'base_price' => (float)($_POST['base_price'] ?? 0),
            'conditions_text' => trim((string)($_POST['conditions_text'] ?? '')),
            'stock' => (int)($_POST['stock'] ?? 0),
        ];

        $themeIds = array_map(static fn(array $t): int => (int)$t['id'], Menu::themes());
        $regimeIds = array_map(static fn(array $r): int => (int)$r['id'], Menu::regimes());

        if ($data['title'] === '' || $data['min_people'] <= 0 || $data['base_price'] <= 0 || !in_array($data['theme_id'], $themeIds, true) || !in_array($data['regime_id'], $regimeIds, true)) {
            flash('error', 'Veuillez selectionner un theme et un regime valides.');
            redirect('/employee/menus');
        }

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        try {
            Menu::save($data, $id);
            flash('success', 'Menu enregistre.');
        } catch (PDOException $e) {
            flash('error', 'Erreur enregistrement menu: ' . $e->getMessage());
        }

        redirect('/employee/menus');
    }

    public function deleteMenu(string $id): void
    {
        require_role('employe');

        try {
            Menu::delete((int)$id);
            flash('success', 'Menu supprime.');
        } catch (PDOException $e) {
            try {
                Menu::setActive((int)$id, false);
                flash('success', 'Menu desactive (suppression impossible car lie a des donnees).');
            } catch (PDOException $e2) {
                flash('error', 'Impossible de supprimer ce menu. Si besoin, ajoutez la colonne menus.is_active puis desactivez-le.');
            }
        }

        redirect('/employee/menus');
    }

    public function deactivateMenu(string $id): void
    {
        require_role('employe');
        Menu::setActive((int)$id, false);
        flash('success', 'Menu desactive.');
        redirect('/employee/menus');
    }

    public function activateMenu(string $id): void
    {
        require_role('employe');
        Menu::setActive((int)$id, true);
        flash('success', 'Menu reactive.');
        redirect('/employee/menus');
    }

    public function saveDish(): void
    {
        require_role('employe');

        $type = trim((string)($_POST['type'] ?? ''));
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if ($name === '' || !in_array($type, ['entree', 'plat', 'dessert'], true)) {
            flash('error', 'Nom et type de plat obligatoires.');
            redirect('/employee/menus');
        }

        try {
            Dish::save([
                'name' => $name,
                'type' => $type,
                'description' => $description,
            ], $id);
            flash('success', 'Plat enregistre.');
        } catch (PDOException $e) {
            flash('error', 'Erreur enregistrement plat: ' . $e->getMessage());
        }

        redirect('/employee/menus');
    }

    public function deleteDish(string $id): void
    {
        require_role('employe');

        try {
            Dish::delete((int)$id);
            flash('success', 'Plat supprime.');
        } catch (PDOException $e) {
            flash('error', 'Impossible de supprimer ce plat car il est utilise dans un menu.');
        }

        redirect('/employee/menus');
    }

    public function saveSchedule(): void
    {
        require_role('employe');

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $dayOfWeek = (int)($_POST['day_of_week'] ?? 0);
        $isClosed = isset($_POST['is_closed']) ? 1 : 0;

        $openTime = trim((string)($_POST['open_time'] ?? ''));
        $closeTime = trim((string)($_POST['close_time'] ?? ''));
        $openTime2 = trim((string)($_POST['open_time2'] ?? ''));
        $closeTime2 = trim((string)($_POST['close_time2'] ?? ''));

        if ($dayOfWeek < 1 || $dayOfWeek > 7) {
            flash('error', 'Jour invalide.');
            redirect('/employee/menus');
        }

        if ($isClosed === 0 && ($openTime === '' || $closeTime === '')) {
            flash('error', 'Les horaires principaux sont obligatoires si le jour est ouvert.');
            redirect('/employee/menus');
        }

        if (($openTime2 === '') xor ($closeTime2 === '')) {
            flash('error', 'Le second creneau doit avoir une heure de debut et de fin.');
            redirect('/employee/menus');
        }

        if ($isClosed === 1) {
            $openTime = '';
            $closeTime = '';
            $openTime2 = '';
            $closeTime2 = '';
        }

        try {
            Schedule::save([
                'day_of_week' => $dayOfWeek,
                'open_time' => $openTime !== '' ? $openTime : null,
                'close_time' => $closeTime !== '' ? $closeTime : null,
                'open_time2' => $openTime2 !== '' ? $openTime2 : null,
                'close_time2' => $closeTime2 !== '' ? $closeTime2 : null,
                'is_closed' => $isClosed,
            ], $id);
            flash('success', 'Horaires enregistres.');
        } catch (PDOException $e) {
            flash('error', 'Erreur enregistrement horaires: ' . $e->getMessage());
        }

        redirect('/employee/menus');
    }

    public function validateReview(string $id): void
    {
        require_role('employe');
        Review::validate((int)$id, true);
        flash('success', 'Avis valide.');
        redirect('/employee');
    }

    public function rejectReview(string $id): void
    {
        require_role('employe');
        Review::validate((int)$id, false);
        flash('success', 'Avis refuse.');
        redirect('/employee');
    }
}
