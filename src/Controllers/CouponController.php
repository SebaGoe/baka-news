<?php
declare(strict_types=1);

namespace Baka\Controllers;

use Baka\Content;
use Baka\View;

final class CouponController
{
    public function index(): string
    {
        $redeemed = $_SESSION['redeemed'] ?? [];
        return View::render('pages/coupons', [
            'title'      => 'Baka News — Coupon Vault',
            'coupons'    => Content::coupons(),
            'redeemed'   => $redeemed,
            'categories' => Content::categories(),
        ]);
    }

    /** POST /coupons/redeem  {id}. Returns JSON with a fake barcode. */
    public function redeem(): void
    {
        $id = $_POST['id'] ?? '';
        $coupon = Content::coupon($id);
        if (!$coupon) {
            json_out(['ok' => false, 'error' => 'No such coupon. Nice try, though.'], 404);
        }

        $_SESSION['redeemed'][$id] = true;

        json_out([
            'ok'       => true,
            'id'       => $id,
            'serial'   => strtoupper(substr(md5($id . microtime()), 0, 12)),
            'message'  => 'Redeemed. Your imaginary savings are on the way.',
        ]);
    }

}
