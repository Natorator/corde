<?php

namespace Abimo;

class Helper
{
    /**
     * Pretty-print the input.
     *
     * @param mixed $input
     * @param bool $print
     *
     * @return void
     */
    public function debug($input, $print = true)
    {
        echo '<pre>';

        ob_start();

        if ($print) {
            print_r($input);
        } else {
            var_dump($input);
        }

        $content = ob_get_contents();
        ob_end_clean();

        echo htmlspecialchars($content, ENT_QUOTES).'</pre>';
    }
}
