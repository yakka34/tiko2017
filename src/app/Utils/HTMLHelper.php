<?php

namespace App\Utils;


class HTMLHelper {

    public static function filter_bad_tags($html) {
        return strip_tags($html,
            '<b><i><div><span><table><tr><td><th><tbody><img><p><h1><h2><h3><h4><h5><h6><br><ul><li><ol><strong><em><sup><sub><code><pre><blockquote>'
        );
    }

}