<?php

return (new PhpCsFixer\Config)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in('./src/')
            ->in('./config/')
            ->in('./tests/')
            ->in('./translations/')
    )
    ->setRules([
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => ['tokens' => ['extra', 'curly_brace_block']],
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_order' => true,
        'phpdoc_types_order' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
    ]);
