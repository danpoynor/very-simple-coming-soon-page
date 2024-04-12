<?php
// Get plugin settings
$vscsp_options = get_option('vscsp_options');

// Set default values if not set
$background_color = isset($vscsp_options['background_color']) ? sanitize_hex_color($vscsp_options['background_color']) : '#ffffff';
$text_color = isset($vscsp_options['text_color']) ? sanitize_hex_color($vscsp_options['text_color']) : '#000000';
$image = isset($vscsp_options['image']) ? esc_url($vscsp_options['image']) : '';
$message = isset($vscsp_options['message']) ? sanitize_text_field($vscsp_options['message']) : 'Coming Soon';
$language = isset($vscsp_options['language']) ? $vscsp_options['language'] : 'en';
$font_family = isset($vscsp_options['font_family']) ? $vscsp_options['font_family'] : 'default';
$font_weight = isset($vscsp_options['bold_text']) && $vscsp_options['bold_text'] ? 'bold' : 'normal';

// Output the coming soon page
?>
<!DOCTYPE html>
<html lang="<?php echo esc_html($language); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($message); ?></title>
    <style>
        body {
            background-color: <?php echo esc_html($background_color); ?>;
            color: <?php echo esc_html($text_color); ?>;
            font-family: <?php echo esc_html($font_family); ?>;
            text-align: center;
        }
        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        img {
            max-width: 240px;
            height: auto;
        }
        h1 {
            font-weight: <?php echo esc_html($font_weight); ?>;
            margin-bottom: 6rem;
        }
    </style>
</head>
<body>
    <main>
        <?php if ($image): ?>
            <img src="<?php echo esc_url($image); ?>" alt="Coming Soon">
        <?php endif; ?>
        <h1><?php echo esc_html($message); ?></h1>
    </main>
</body>
</html>