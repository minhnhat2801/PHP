<?php
class Format {
    public function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public function textShorten($text, $limit = 50) {
        return (strlen($text) > $limit) ? substr($text, 0, $limit) . "..." : $text;
    }

    public function formatDate($date) {
        return date("d-m-Y H:i:s", strtotime($date));
    }
}
?>
