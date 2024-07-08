<?php
// Function to generate a random card number
function generateCardNumber() {
    // Generate a random 11-digit number
    $number = sprintf('%011d', mt_rand(0, 99999999999));

    // Format the number as XXX XXX XXX XX
    return substr($number, 0, 3) . ' ' . substr($number, 3, 3) . ' ' . substr($number, 6, 3) . ' ' . substr($number, 9, 2);
}

// Function to generate a random PIN
function generatePIN() {
    // Generate a random 3-5 digit PIN
    return sprintf('%0*d', mt_rand(3, 5), mt_rand(0, 99999));
}

// Check if the form has been submitted
if (isset($_POST['name'])) {
    // Sanitize the user input
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');

    // Set image dimensions
    $width = 1920;
    $height = 1080;

    // Load existing data.json or initialize empty array if not exists
    $data = file_exists('data.json') ? json_decode(file_get_contents('data.json'), true) : [];

    // Generate unique card number
    do {
        $cardNumber = generateCardNumber();
    } while (isset($data[$cardNumber])); // Check if card number already exists

    // Generate PIN
    $pin = generatePIN();

    // Store generated credentials in data.json
    $data[$cardNumber] = [
        'name' => $name,
        'pin' => $pin
    ];

    // Save updated data.json
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

    // Create a new image
    $image = imagecreatetruecolor($width, $height);

    // Load the background image
    $bgImage = imagecreatefrompng('retrofron.png');
    if (!$bgImage) {
        die('Failed to load background image.');
    }

    // Copy the background image to the new image
    imagecopy($image, $bgImage, 0, 0, 0, 0, $width, $height);

    // Text colors (adjusted for visibility against the new background)
    $white = imagecolorallocate($image, 255, 255, 255);

    // Font path (replace with your font path)
    $font = __DIR__ . '/fonts/font2.ttf';
    if (!file_exists($font)) {
        die('Font file not found.');
    }

    // Define text positions and sizes
    $ritreo_font_size = 90;
    $ritreo_x = 750;  // Adjust this for horizontal position of 'RITREO'
    $ritreo_y = 200;  // Adjust this for vertical position of 'RITREO'

    $card_number_font_size = 60;
    $card_number_x = 660;  // Adjust this for horizontal position of card number
    $card_number_y = 400;  // Adjust this for vertical position of card number

    $name_font_size = 50;
    $name_x = 171;  // Adjust this for horizontal position of name
    $name_y = 600;  // Adjust this for vertical position of name

    $note_font_size = 20;
    $note_x = 400;  // Adjust this for horizontal position of note
    $note_y = 1050;  // Adjust this for vertical position of note

    $pin_font_size = 35;
    $pin_x = 400;  // Adjust this for horizontal position of PIN
    $pin_y = 950;  // Adjust this for vertical position of PIN

    // Add text (replace placeholder text with your actual content)
    imagettftext($image, $ritreo_font_size, 0, $ritreo_x, $ritreo_y, $white, $font, 'Retro');
    imagettftext($image, $card_number_font_size, 0, $card_number_x, $card_number_y, $white, $font, $cardNumber);
    imagettftext($image, $name_font_size, 0, $name_x, $name_y, $white, $font, strtoupper($name));
    imagettftext($image, $note_font_size, 0, $note_x, $note_y, $white, $font, 'RETRO SUPPORT TEAM WILL NEVER ASK FOR YOUR PIN');
    imagettftext($image, $pin_font_size, 0, $pin_x, $pin_y, $white, $font, 'PIN: ' . $pin);

    // Output the image to the browser
    header('Content-Type: image/png');
    imagepng($image);

    // Free up memory
    imagedestroy($image);
    imagedestroy($bgImage);
}
?>

  <!DOCTYPE html>
  <html>
  <head>
      <title>Retro Card Generator</title>
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
      <style>
          body {
              font-family: 'Open Sans', sans-serif;
              background-color: #f5f5f5;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              height: 100vh;
              margin: 0;
          }

          h1 {
              color: #333;
              margin-bottom: 20px;
          }

          form {
              background-color: white;
              padding: 40px;
              border-radius: 8px;
              box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
              width: 400px;
          }

          input[type="text"] {
              width: 100%;
              padding: 10px;
              font-size: 16px;
              border: 1px solid #ccc;
              border-radius: 4px;
              margin-bottom: 20px;
          }

          button {
              background-color: #007bff;
              color: white;
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              font-size: 16px;
              cursor: pointer;
              width: 100%;
          }

          button:hover {
              background-color: #0056b3;
          }
      </style>
  </head>
  <body>
      <h1>Retro Card Generator</h1>
      <form method="post" action="">
          <label for="name">Name:</label>
          <input type="text" name="name" id="name" required>
          <button type="submit">Generate Card</button>
      </form>
  </body>
  </html>
