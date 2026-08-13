<?php
declare(strict_types=1);

namespace Baka;

/**
 * Dead-simple PHP template renderer.
 * render('pages/landing', [...]) -> wraps the page in Views/layout.php
 * partial('partials/masthead', [...]) -> renders without the layout wrapper
 */
final class View
{
    public static function render(string $template, array $data = []): string
    {
        $content = self::partial($template, $data);
        return self::partial('layout', array_merge($data, ['content' => $content]));
    }

    public static function partial(string $template, array $data = []): string
    {
        $file = BAKA_ROOT . '/src/Views/' . $template . '.php';
        if (!is_file($file)) {
            return "<!-- missing view: {$template} -->";
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
